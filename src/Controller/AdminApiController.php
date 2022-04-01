<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AdminApiController extends AbstractController
{
    protected $client;

    protected $clientId;

    protected $clientSecret;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->clientId = "8f3211b2a50745db8acbcad4881a1ad9"; /* your client id from api client found in PIQ BO admin->api clients */
        $this->clientSecret = "0a183cebe0714aa09927ac43f7057aba";
    }

    public function kyc() {
        $response = $this->client->request('POST', 'https://test-api.paymentiq.io/paymentiq/admin/v1/kyc/check?merchantId=199001', ['headers' => [
                'Content-Type: application/json',
                'Authorization: Bearer '. base64_encode($this->clientId . ':' . $this->clientSecret),
            ],
            'body' => '{
  "userId": "111122223333111",
  "merchantKycStatus": "hi",
  "name": "Viktor Rydberg",
  "firstName": "Viktor",
  "lastName": "Rydberg",
  "sex": "MALE",
  "dob": "1971-02-01",
  "street": "SOMMARBO 207",
  "city": "JORDBRO",
  "state": "",
  "zip": "13767",
  "country": "SWE",
  "ip": "192.0.0.0",
  "email": "abdallah.salameh@bambora.com",
  "mobile": "24161472",
  "ssn":"",
  "attributes": {
    "success": "http://google.come/sucess",
    "pending": "http://google.come/pending",
    "failed": "http://google.come/failed"
  }
}'
        ]);

        $content = $response->toArray();
        dd($response);
    }
}