<?php
namespace App\Mixin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BuilderMixin 
{
	public function whenTldMatches()
	{
		return function($tld, $callback) {
            if (Request::tldIs($tld)) {//(1)
                call_user_func($callback->bindTo($this));
            }
            else{
                 die;
            }
        };
	}
}

/*
Note
 */
//(1): this  function ($tld) will be stored in $macro[] in Macroable.php