<?php

namespace App\Services;

use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalService
{
    private $apiContext;

    public function __construct()
    {
        // Initialize PayPal API context with credentials
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                "Aa0_wbmmdXDC8sdue2ckB0X8K9XyvQu_2YMlOVyCEXpbb_xn3b4XcKUiBGSObOGox4_FzFMJVpMkhm0c",     // ClientID from config
                "EIKrb60o7SV8K9n8STn20nT2C5ithwGvwlXW0ZZAQPrO-bF3Stzq1eeU0wjF-fpDMyyLvN5mjXx562ln"         // ClientSecret from config
            )
        );
        $this->apiContext->setConfig([
            'mode' => 'sandbox',
        ]);
    }

    // Method to create a payment
    public function createPayment($amount)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setTotal($amount); // Total amount
        $amount->setCurrency('USD');

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription('Payment for Sample Product');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.success'))
                     ->setCancelUrl(route('paypal.cancel'));

        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);
            return $payment->getApprovalLink();
        } catch (\Exception $ex) {
            return false;
        }
    }

    // Method to execute a payment
    public function executePayment($paymentId, $payerId)
    {
        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            $result = $payment->execute($execution, $this->apiContext);
            return $result;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
