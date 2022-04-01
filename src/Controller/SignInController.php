<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class SignInController extends AbstractController
{

    private $client;

    private $clientId;

    private $uri;

    private $clientSecret;

    private $requestStack;

    public function __construct(HttpClientInterface $client, RequestStack $requestStack)
    {
        $this->client = $client; /* Our client that will issue requests */
        $this->clientId = "8f3211b2a50745db8acbcad4881a1ad9"; /* your client id from api client found in PIQ BO admin->api clients */
        $this->clientSecret = "0a183cebe0714aa09927ac43f7057aba";/* your client secret from api client found in PIQ BO admin->api clients */
        $this->uri = 'https://a3ff-185-139-247-226.ngrok.io'; /* set to the ngrok url */
        $this->requestStack = $requestStack;
    }


    public function initialize(Request $request)
    {

        if ($request->query->has('amount') && $request->query->has('currency')) {

            $amount = $request->get('amount');
            $currency = $request->get('currency');

            $response = $this->client->request(
                'GET',
                'https://test-api.paymentiq.io/paymentiq/oauth/authorize?client_id=' . $this->clientId . '&identity_provider=trustly&country=FI&provider=trustly&redirect_uri=' . $this->uri . '/oauth2/callback&locale=en_FI&currency='.$request->query->get('currency').'&amount='. $request->query->get('amount'));


        } else {
            $response = $this->client->request(
                'GET',
                'https://test-api.paymentiq.io/paymentiq/oauth/authorize?client_id=' . $this->clientId . '&identity_provider=trustly&showAccount=false&country=SE&redirect_uri=' . $this->uri . '/oauth2/callback&locale=sv_SE&attributes[user_type]=beginner'
            );
        }


        $content = $response->getContent();

        $parameters = json_decode($content, true);

        $output = $parameters['redirectOutput'];

        return $this->redirect($output['url']);

    }

    public function signin(Request $request) {

        $response = new JsonResponse();
        $response->setData(['userId' => 'user_123', 'sessionId' => 'session123', 'success' => true, 'balance' => '1000', 'balanceCy' => 'EUR']);
        $response->send();

    }


    public function response(Request $request)
    {

        $session = $this->requestStack->getSession();

        $code = $request->query->get('code');

        if (!$session->has('access-token')) {

            $response = $this->client->request('POST', 'https://test-api.paymentiq.io/paymentiq/oauth/token?grant_type=authorization_code&code='.$code.'&redirect_uri='.$this->uri.'/oauth2/callback&client_id='.$this->clientId, ['headers' => [
                'Accept: */*',
                'Authorization: Basic '. base64_encode($this->clientId . ':' . $this->clientSecret),
                ],
            ]);

            $content = $response->toArray();

            $session->set('access-token', $content['access_token']);

        } else {

            $token = $session->get('access-token');

            $response = $this->client->request(
                'GET',
                'https://test-api.paymentiq.io/paymentiq/oauth/check_token?token='.$token, ['headers' => [
                    'Accept: */*',
                    'Authorization: Basic '. base64_encode($this->clientId . ':' . $this->clientSecret),
            ]]);

            $content = $response->toArray();
        }


        $order = new Order();
        $order->setAmount('1000');

        $form = $this->createForm(OrderType::class, $order, [
            'action' => $this->generateUrl('trustly'),
            'method' => 'POST',
        ]);


        return $this->renderForm('signin/completed.html.twig', [
            'content' => $content,
            'form' => $form,
        ]);
    }

    public function success()
    {

        return $this->render('signin/success.html.twig');
    }

    public function cancel()
    {
        return $this->render('signin/cancel.html.twig');
    }


}