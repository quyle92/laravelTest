<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Book;

class CheckoutBookController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {   
  
        $user = Auth::user(); 
        $book_id = $request->book_id;
        $book = Book::find( $book_id );
        $book->checkout($user);

    }
}
