<?php

namespace App\Http\Controllers;
use App\Billing\PaymentGatewayContract;
use App\Orders\OrderDetails;
use Illuminate\Http\Request;


class PayOrderController extends Controller
{	

    public function store( OrderDetails $orderDetails, PaymentGatewayContract $paymentGatewayContract)
    {	

    	$order = $orderDetails->all(3);
    	dd($paymentGatewayContract->charge(11));

    }
}
