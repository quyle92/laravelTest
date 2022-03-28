<?php
namespace App\Billing;
use App\Billing\PaymentGatewayContract;
use Illuminate\Support\Str;


class BankPaymentGateway implements PaymentGatewayContract
{	
	private $currency;
	private $discount = 0;
		
	public function __construct( $currency )
	{
		$this->currency = $currency;

	}

	public function setDiscount( $discount )
	{ 
		$this->discount = $discount;
	}

	public function charge( $amount )
	{	
		//charge the bank
		return response([
			'amount' => $amount - $this->discount,
			'confirmation_number' => Str::random(),
			'currency' => $this->currency,
			'discount' => $this->discount
		]);
	}


}

?>