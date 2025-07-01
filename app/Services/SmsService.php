<?php

namespace App\Services;

// use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
   

class SmsService
{
     protected $baseUrl;
     protected $apiKey;

    public function __construct()
    {
        // $this->baseUrl = env('INFOBIP_BASE_URL');
        // $this->apiKey = env('INFOBIP_API_KEY'); // store it in .env
        $this->baseUrl = config('services.infobip.base_url');
        $this->apiKey = config('services.infobip.api_key');

    }


    public function sendPinCode(string $appId, string $phoneNumber)
    {
    $url = "{$this->baseUrl}/2fa/2/pin";

    $payload = [
        "applicationId" => $appId,
        "messageId" => "default",  // Make sure you include this line!
        "to" => $phoneNumber,
    ];

    $response = Http::withHeaders([
        'Authorization' => 'App ' . $this->apiKey,
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ])->post($url, $payload);

    return $response->json();
    }

    public function verifyPinCode(string $pinId, string $pinCode)
    {
    $url = "{$this->baseUrl}/2fa/2/pin/{$pinId}/verify";

    $response = Http::withHeaders([
        'Authorization' => 'App ' . $this->apiKey,
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ])->post($url, [
        'pin' => $pinCode,
    ]);

    return $response->json();
    }

}