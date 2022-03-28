<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Support\Str;
use App\QueryFilters\Pipe;

class Sort extends Pipe
{
    public function applyFilter($builder)
    {
       return $builder->where($this->filterName(),  request()->input($this->filterName()));
    }
}