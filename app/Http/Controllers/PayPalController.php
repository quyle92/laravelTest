<?php

namespace App\Http\Controllers;

use App\Order;
use App\PayPal;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Crypt;

/**
 * Class PayPalController
 * @package App\Http\Controllers
 */
class PayPalController extends Controller
{
    /**
     * @param Request $request
     */
    public function form(Request $request, $order_id = null)
    {   
        $order_id = $order_id ?: encrypt(1);

        $order = Order::findOrFail(($order_id));

        return view('test', compact('order'));
    }

    /**
     * @param $order_id
     * @param Request $request
     */
    public function checkout($order_id, Request $request)
    {
        $order = Order::findOrFail(decrypt($order_id));

        $paypal = new PayPal;

        $response = $paypal->purchase([
            'amount' => $paypal->formatAmount($order->amount),
            'transactionId' => uniqid(),
            'currency' => 'USD',
            'cancelUrl' => $paypal->getCancelUrl($order),
            'returnUrl' => $paypal->getReturnUrl($order),
        ]);

        if ($response->isRedirect()) {
            $response->redirect();
        }

        return redirect()->back()->with([
            'message' => $response->getMessage(),
        ]);
    }

    /**
     * @param $order_id
     * @param Request $request
     * @return mixed
     */
    public function completed($order_id, Request $request)
    {
        $order = Order::findOrFail($order_id);

        $paypal = new PayPal;

        $response = $paypal->complete([
            'amount' => $paypal->formatAmount($order->amount),
            //'transactionId' => $order->id,
            'currency' => 'USD',
            //'cancelUrl' => $paypal->getCancelUrl($order),
            //'returnUrl' => $paypal->getReturnUrl($order),
            //'notifyUrl' => $paypal->getNotifyUrl($order),
        ]);

        if ($response->isSuccessful()) {
            $order->update([
                'transaction_id' => $response->getTransactionReference(),
                'payment_status' => Order::PAYMENT_COMPLETED,
            ]);

            return redirect()->route('app.home', ($order_id))->with([
                'message' => 'You recent payment is sucessful with reference code ' . $response->getTransactionReference(),
            ]);
        }

        return redirect()->back()->with([
            'message' => $response->getMessage(),
        ]);
    }

    /**
     * @param $order_id
     */
    public function cancelled($order_id)
    {
        $order = Order::findOrFail($order_id);

        return redirect()->route('app.home', encrypt($order_id))->with([
            'message' => 'You have cancelled your recent PayPal payment !',
        ]);
    }

    /**
     * @param $order_id
     * @param $env
     */
    public function webhook($order_id, $env)
    {
        // to do with next blog post
    }
}

/*
Note
 */
//Ref: https://artisansweb.net/paypal-payment-gateway-integration-in-php-using-paypal-rest-api/
//https://sujipthapa.co/blog/a-guide-to-integrate-omnipay-paypal-with-laravel