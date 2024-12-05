<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\Payment;
use Srmklive\PayPal\Services\PayPal;
use Stripe\Charge;
use Stripe\Stripe;


class PaymentController extends Controller
{
    public $gateway;
    public $completePaymentUrl;

    public function __construct()
    {
        $this->gateway = Omnipay::create('Stripe\PaymentIntents');
        $this->gateway->setApiKey(env('STRIPE_SECRET_KEY'));
        $this->completePaymentUrl = url('confirm');
    }
    public function createPayment($id)
    {
        $invoice = Invoice::findorfail($id);
        $pm = PaymentMethod::findOrFail($invoice->payment_method);
        if ($pm->mechant_company == "PayPal") {
            return redirect()->route("create.paypal.payment", ["invoice_id" => $id]);
        }
        else
        {
            return redirect()->route("create.stripe.payment", ["invoice_id" => $id]);
        }
    }


    public function CreatepPaypalPayment($invoice_id)
    {

        $this->makepaypalcurrentsecrets($invoice_id);
        $invoice = Invoice::find($invoice_id);
        $provider = new PayPal;
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('payment.paypal.success'),
                "cancel_url" => route('payment.paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $invoice->total_amount,
                    ]
                ]
            ]
        ]);

        if (isset($response['id'])) {
            // Redirect to PayPal for approval
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('payment.cancel');
        }
    }
    public function capturePaypalPayment(Request $request)
    {
        $provider = new PayPal;
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());

        $response = $provider->capturePaymentOrder($request['token']);

        if ($response['status'] == 'COMPLETED') {
            // Payment was successful
            return redirect()->route('home')->with('success', 'Payment successful!');
        } else {
            return redirect()->route('payment.cancel');
        }
    }

    public function cancelPaypalPayment()
    {
        // Handle the case where the payment was canceled
        return redirect()->route('invoices.index')->with('error', 'Payment canceled!');
    }


    public function makepaypalcurrentsecrets($invoice)
    {
        $pm = Invoice::findorfail($invoice)->payment_method;
        $secrets = PaymentMethod::findorfail($pm);
        if (config("paypal.mode") == "live") {
            config(["paypal.live.client_id" => $secrets->client_id]);
            config(["paypal.live.client_secret" => $secrets->secret_key]);
        } else {
            config(["paypal.sandbox.client_id" => $secrets->client_id]);
            config(["paypal.sandbox.client_secret" => $secrets->secret_key]);
        }
    }

    public function CreatepStripePayment($invoice_id)
    {
        $invoice = invoice::findorfail($invoice_id);
        config(["cashier.key" => $invoice->payment_method_detail->public_key]);
       
        return view('invoices.paymentform',compact("invoice"));
    }

    public function stripepaymentprocess(request $request)
    {
        $invoice = invoice::findorfail($request->invoice_id);
        config(["cashier.key" => $invoice->payment_method_detail->public_key]);
        config(["cashier.secret" => $invoice->payment_method_detail->secret_key]);
        $invoice = Invoice::findorfail($request->invoice_id);
        try {
            // Make the charge to Stripe
            $charge = Charge::create([
                'amount' => $invoice->total_amount * 100, // Amount in cents
                'currency' => 'usd',
                'source' => $request->token,
                'description' => 'Inovice Paid Ref No .#'.$invoice->invoice_id,
            ]);
            Payment::create([
                    "txn_id" => $charge["id"],
                    "invoice_id" => $invoice->id,
                    "amount" => $invoice->total_amount,
                    "status" => $charge["status"],
                    "pm" => "stripe"
            ]);
            $invoice->status = "paid";
            $invoice->save();
            return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
        }
        catch (\Exception $e) {
            // Payment failed, handle error
            return redirect()->route('invoices.index')->with('error', $e->getMessage());
        }
    }






















    // public function charge(Request $request)
    // {
    //     if($request->input('stripeToken'))
    //     {
    //         $token = $request->input('stripeToken');

    //         $response = $this->gateway->authorize([
    //             'amount' => $request->input('amount'),
    //             'currency' => env('STRIPE_CURRENCY'),
    //             'description' => 'This is a X purchase transaction.',
    //             'token' => $token,
    //             'returnUrl' => $this->completePaymentUrl,
    //             'confirm' => true,
    //         ])->send();

    //         if($response->isSuccessful())
    //         {
    //             $response = $this->gateway->capture([
    //                 'amount' => $request->input('amount'),
    //                 'currency' => env('STRIPE_CURRENCY'),
    //                 'paymentIntentReference' => $response->getPaymentIntentReference(),
    //             ])->send();

    //             $arr_payment_data = $response->getData();

    //             $this->store_payment([
    //                 'payment_id' => $arr_payment_data['id'],
    //                 'payer_email' => $request->input('email'),
    //                 'amount' => $arr_payment_data['amount']/100,
    //                 'currency' => env('STRIPE_CURRENCY'),
    //                 'payment_status' => $arr_payment_data['status'],
    //             ]);

    //             return redirect("payment")->with("success", "Payment is successful. Your payment id is: ". $arr_payment_data['id']);
    //         }
    //         elseif($response->isRedirect())
    //         {
    //             session(['payer_email' => $request->input('email')]);
    //             $response->redirect();
    //         }
    //         else
    //         {
    //             return redirect("payment")->with("error", $response->getMessage());
    //         }
    //     }
    // }

    // public function confirm(Request $request)
    // {
    //     $response = $this->gateway->confirm([
    //         'paymentIntentReference' => $request->input('payment_intent'),
    //         'returnUrl' => $this->completePaymentUrl,
    //     ])->send();

    //     if($response->isSuccessful())
    //     {
    //         $response = $this->gateway->capture([
    //             'amount' => $request->input('amount'),
    //             'currency' => env('STRIPE_CURRENCY'),
    //             'paymentIntentReference' => $request->input('payment_intent'),
    //         ])->send();

    //         $arr_payment_data = $response->getData();

    //         $this->store_payment([
    //             'payment_id' => $arr_payment_data['id'],
    //             'payer_email' => session('payer_email'),
    //             'amount' => $arr_payment_data['amount']/100,
    //             'currency' => env('STRIPE_CURRENCY'),
    //             'payment_status' => $arr_payment_data['status'],
    //         ]);

    //         return redirect("payment")->with("success", "Payment is successful. Your payment id is: ". $arr_payment_data['id']);
    //     }
    //     else
    //     {
    //         return redirect("payment")->with("error", $response->getMessage());
    //     }
    // }

    // public function store_payment($arr_data = [])
    // {
    //     $isPaymentExist = Payment::where('payment_id', $arr_data['payment_id'])->first();

    //     if(!$isPaymentExist)
    //     {
    //         $payment = new Payment;
    //         $payment->payment_id = $arr_data['payment_id'];
    //         $payment->payer_email = $arr_data['payer_email'];
    //         $payment->amount = $arr_data['amount'];
    //         $payment->currency = env('STRIPE_CURRENCY');
    //         $payment->payment_status = $arr_data['payment_status'];
    //         $payment->save();
    //     }
    // }

}
