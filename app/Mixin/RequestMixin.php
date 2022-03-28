<?php
namespace App\Mixin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestMixin 
{
	public function tldIs()
	{	
		 return  function ($tld) {
            return Str::is('*.' . $tld, 'abc.com');
        }; //(1)
	}

}

/*
Note
 */
//(1): this  function ($tld) will be stored in $macro[] in Macroable.php