<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //use HasFactory;

    protected $guarded  = []; 

    public function commentable()
    {
        return $this->morphTo();
    }
}
