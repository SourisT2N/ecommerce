<?php
namespace App\Services;

use Exception;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PayPalService
{
    public static function checkOrder($orderId) 
    {
        $client = self::client();
        $response = $client->execute(new OrdersGetRequest($orderId));
        if($response->result->status != 'COMPLETED')
            return false;
        return true;
    }

    public static function client()
    {
        return new PayPalHttpClient(new SandboxEnvironment(env('PAYPAL_CLIENT_ID'),env('PAYPAL_SECRET')));
    }
}