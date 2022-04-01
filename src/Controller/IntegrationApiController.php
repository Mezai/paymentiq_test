<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class IntegrationApiController extends AbstractController
{

    protected $response;

    public function __construct(JsonResponse $response)
    {
        $this->response = $response;
    }


    public function verifyuser() {

        $this->response->setData(['userId' => '12', 'success' => true, 'balance' => '1000', 'balanceCy' => 'USD', 'country' => 'SWE', "firstName" => "Andrei",
            "lastName" => "Nikitsin",
            "street" => "Minsk",
            'email' => 'test@test.com',
            'mobile' => '123123123',
            "city" => 'Troll',
            "zip" => '1234',
            'state' => 'MH',
            'dob' => '1999-01-01',
            'kycStatus' => 'UNVERIFIED',
            'attributes' => ['foo' => 'bar']
            ]);
        $this->response->send();

    }

    public function authorize(LoggerInterface $logger) {
        $request = Request::createFromGlobals();
        $content = $request->getContent();
        $parameters = json_decode($content, true);


        $this->response->setData(['userId' => '12', 'sessionId' => 'session123', 'errMsg' => 'below limit', 'errCode' => '102', 'success' => true, 'balance' => '1000', 'balanceCy' => 'EUR', 'country' => 'EUR']);
        $this->response->send();

    }

    public function transfer() {

        $this->response->setData(['userId' => '12', 'sessionId' => 'session123', 'success' => true, 'balance' => '1000', 'balanceCy' => 'EUR']);
        $this->response->send();
    }

    public function cancel() {

        $this->response->setData(['userId' => '12', 'sessionId' => 'session123', 'success' => true, 'balance' => '1000', 'balanceCy' => 'EUR']);
        $this->response->send();

    }

}