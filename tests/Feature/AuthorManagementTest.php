<?php

namespace Tests\Feature;

use App\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function store_author()
    {   
        $this->withoutExceptionHandling();

        $response = $this->post( '/author', $this->data() );

        $author = Author::first();

        $response->assertOk();

        $this->assertCount(1, Author::all());

        $this->assertInstanceOf(Carbon::class, $author->dob);
        
        $this->assertEquals('1992-01-01', $author->dob->format('Y-m-d'));

    }

     /**
     * @test
     */
    public function author_name_is_required()
    { 
        $response = $this->post( '/author', array_merge($this->data(), ['name' => ''] ) );

        $response->assertSessionHasErrors('name');
    }

    /**
     * @test
     */
    public function author_can_be_updated()
    {   
       $this->withoutExceptionHandling();

       $this->post( '/author', $this->data() );

       $author = Author::first();

       $response = $this->patch('/author/' . $author->id, [
            'name' => 'thanh quy'
        ]);

       $author = Author::first();

       $this->assertCount(1, Author::all());
       $this->assertEquals('thanh quy', $author->name);
       $response->assertRedirect('/author/' . $author->id . '/' .  Str::slug($author->name));
    }

    /**
     * @test
     */
    public function author_can_be_deleted()
    {   
       $this->withoutExceptionHandling();

       $this->post( '/author', $this->data() );

        $author = Author::first();

       $this->delete( '/author/' . $author->id);

       $this->assertCount(0, Author::all());

    }



    public function data()
    {
        return [
            'name' => 'quy',
            'dob' => '01/01/1992'
        ];
    }
}
