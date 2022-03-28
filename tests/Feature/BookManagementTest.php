<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{   
    use RefreshDatabase;
    /**
     *@test
     */
    public function a_book_can_be_added_to_library()
    {   
        $this->withoutExceptionHandling();

        $response = $this->post('/book',$this->data());

        $book = Book::first();

       // $response->assertOk();

        $this->assertCount(1, Book::all());

        $response->assertRedirect($book->path());

        // $response->dumpHeaders();

        // $response->dump();
    }

    /**
     *@test
     */
    public function a_title_is_required()
    {   
       // $this->withoutExceptionHandling();

        $response = $this->post('/book', array_merge($this->data(), ['title' => '']));

        $response->assertSessionHasErrors('title');


    }


    /**
     *@test
     */
    public function an_author_is_required()
    {   
        // $this->withoutExceptionHandling();

        $response = $this->post('/book', array_merge( $this->data(), ['author_id' => '']) );

        $response->assertSessionHasErrors('author_id');


    }

    /**
     *@test
     */
    public function a_book_can_be_updated()
    {   
        $this->withoutExceptionHandling();

        $this->post('/book', $this->data());

        $book = Book::first();
        $response = $this->patch($book->path(), [
            'title' => 'Self-dev',
            'author_id' => 'quy',
        ]);

        $this->assertEquals('Self-dev', Book::first()->title);
        $this->assertEquals(1, Book::first()->author_id);

        $response->assertRedirect($book->fresh()->path());

    }

    /**
     * 
     * @test
     * 
     */
    public function a_book_can_be_deleted()
    {   
        $this->withoutExceptionHandling();

        $this->post('/book', [
            'title' => 'Hello world',
            'author' => 'Me'
        ]);
        $this->assertCount(1, Book::all());

        $book = Book::first();

        $response = $this->delete( '/book/' . $book->id );

        $this->assertCount(0, Book::all());

        $response->assertRedirect('/book');


    }

    /**
     * 
     * @test
     * 
     */
    public function a_new_author_is_automatically_added()
    {       
        $this->withoutExceptionHandling();

        $book = $this->post('/book',[
            'title' => 'Cool book',
            'author_id' => 'quy'
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $author = Author::first();
        $this->assertCount(1, Author::all());


    }

    public function testApplication()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                         ->withSession(['foo' => 'bar'])
                         ->get('/');

        $this->assertAuthenticated();    
    }

    public function data() 
    {
        return  [
            'title' => 'Cool Book Title',
            'author_id' => 'quy'
        ];
    }

   


}
