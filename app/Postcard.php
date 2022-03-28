<?php

namespace App;

class Postcard 
{	
	protected static function resolveFacade($name)
	{
		return app()->make($name);
	}

	public static function __callStatic($method, $args)
	{	
		//dd($args);
		return self::resolveFacade('Postcard')->$method(...$args);
	}
}


