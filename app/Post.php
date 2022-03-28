<?php

namespace App;

use App\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Input;

class Post extends Model
{	
	protected $guarded  = [];
	
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function comment()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public static function filterPosts()
    {
        $posts = Post::query();

        $pipeline = app(Pipeline::class)
                ->send($posts)
                ->through([
                    \App\QueryFilters\Active::class,
                    \App\QueryFilters\Sort::class,
                ])
                ->thenReturn();

        return $pipeline->paginate(5);
    }
}
