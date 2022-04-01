<?php


namespace App\Controller;


use App\Entity\Order;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TrustlyDepositController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function process(Request $request)
    {

        $task = new Order();

        $form = $this->createForm(OrderType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $amount = $data->amount;
        }

        $response = $this->client->request('POST', 'https://test-api.paymentiq.io/paymentiq/api/trustly/deposit/process', [
            'json' => ['sessionId' => '123', 'userId' => '123', 'amount' => $amount, 'merchantId' => '199001', 'attributes' => [
                'successUrl' => 'https://localhost:8000/success',
                'failureUrl' => 'https://localhost:8000/cancel'
            ]],
        ]);

        $decodedPayload = $response->toArray();
        $redirectOutput = $decodedPayload['redirectOutput'];
        return $this->redirect($redirectOutput['url']);
    }

}