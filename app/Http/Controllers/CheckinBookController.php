<?php

namespace App\Http\Controllers;

use App\Book;
use App\User;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable; 

class CheckinBookController extends Controller
{
    public function __construct()
    {   
        $this->middleware('auth');
    }

    public function store(Request $request)
    {   
        try
        {
            $user = Auth::user(); 
            //$user = Auth::login(User::first());
            $book_id = $request->book_id;
            $book = Book::findOrFail( $book_id );
            $book->checkin($user);
        }
        catch(\Exception $e) {
            return $e->__toString();//(1)
        }
        

    }
}

/*
Note
 */
//(1): this is to catch all types of exception. Ref: https://stackoverflow.com/a/43567812/11297747
