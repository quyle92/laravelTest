<?php

namespace App;

use App\Author;
use App\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $guarded = [];

    public function path() 
    {
        return '/book/' . $this->id . '-' . Str::slug($this->title);
    }

    public function setAuthorIdAttribute($author_id)
    {   
        $this->attributes['author_id'] = Author::firstOrCreate(['name' => $author_id])->id;
    }

    public function checkout( $user ) 
    {  
        $this->reservations()->create([
            'user_id' => $user->id,
            // 'book_id' => $this->id, ko cần thiết vì cái này đang ở trong book model nên nó sẽ tự add
            'checkout_at' => now()
        ]);

    }

    public function checkin( $user )
    {      
        // dd($user);
        $reservation = Reservation::where('user_id', $user->id)
                ->whereNotNull('checkout_at')
                ->whereNull('checkin_at')->first();

        if(is_null($reservation)) 
        {
            throw new \Exception();
        }

        $reservation->update(['checkin_at' => now()]);
        
    }

    public function reservations()
    {
        return  $this->hasMany(Reservation::class);
    }

}
