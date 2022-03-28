<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthorController extends Controller
{
    public function store(Request $request) 
    {   
        $author = Author::create( $this->validateRequest($request)  );
    }

    protected function validateRequest( Request $request )
    {   
        $data = $request->validate([
            'name' => 'required',
            'dob' => 'required'
        ]);
        return $data;
    }

    public function update(Request $request, Author $author) 
    {   
        $author->update( $request->only(['name'])  );

        return redirect( $author->path() );
    }

    public function destroy(Author $author)
    {
        $author->delete();
        
    }
}
