<?php

// return [
//     /**
//      * API Token Key (string)
//      * Accepted value:
//      * Live Token: https://myfatoorah.readme.io/docs/live-token
//      * Test Token: https://myfatoorah.readme.io/docs/test-token
//      */
//     'api_key' => '',
//     /**
//      * Test Mode (boolean)
//      * Accepted value: true for the test mode or false for the live mode
//      */
//     'test_mode' => true,
//     /**
//      * Country ISO Code (string)
//      * Accepted value: KWT, SAU, ARE, QAT, BHR, OMN, JOD, or EGY.
//      */
//     'country_iso' => 'KWT',
//     /**
//      * Save card (boolean)
//      * Accepted value: true if you want to enable save card options.
//      * You should contact your account manager to enable this feature in your MyFatoorah account as well.
//      */
//     'save_card' => true,
//     /**
//      * Webhook secret key (string)
//      * Enable webhook on your MyFatoorah account setting then paste the secret key here.
//      * The webhook link is: https://{example.com}/myfatoorah/webhook
//      */
//     'webhook_secret_key' => '',
//     /**
//      * Register Apple Pay (boolean)
//      * Set it to true to show the Apple Pay on the checkout page.
//      * First, verify your domain with Apple Pay before you set it to true.
//      * You can either follow the steps here: https://docs.myfatoorah.com/docs/apple-pay#verify-your-domain-with-apple-pay or contact the MyFatoorah support team (tech@myfatoorah.com).
//     */
//     'register_apple_pay' => false


    return [
    'api_key'     => env('MYFATOORAH_API_KEY'),
    'test_mode'   => env('MYFATOORAH_TEST_MODE', true),
    'country_iso' => env('MYFATOORAH_COUNTRY_ISO', 'SAU'),
];

// ];
