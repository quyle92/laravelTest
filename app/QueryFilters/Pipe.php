<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class Pipe
{
    public function handle(Builder $builder, Closure $next)
    {	
    	if( ! request()->has( $this->filterName() ) )
    	{
    		return $next($builder);
    	}

    	$builder = $next($builder);

    	return $this->applyFilter($builder);
    }

    public function filterName()
    {
    	return Str::snake(class_basename($this));
    }

    protected abstract function applyFilter($builder);

    
}