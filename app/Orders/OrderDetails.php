<?php
namespace App\Orders;
use Illuminate\Support\Str;
use App\Billing\PaymentGatewayContract;

class OrderDetails
{   
    private $paymentGatewayContract;
        
    public function __construct( PaymentGatewayContract  $paymentGatewayContract )
    {
        $this->paymentGatewayContract = $paymentGatewayContract;
    }

    public function all($discount)
    {   

        $this->paymentGatewayContract->setDiscount($discount);

        return [
            'name' => 'Kim',
            'address' => '123 North Korea'
        ];
    }


}

?>