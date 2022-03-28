<?php

namespace Tests\Unit;

use App\Book;
use App\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookReservationTest extends TestCase
{   
     use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @test void
     */
    public function a_book_can_be_checked_out()
    {    
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();


        $book->checkout( $user );

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals(now(), Reservation::first()->checkout_at);
    }

    /**
     * A basic unit test example.
     *
     * @test void
     */
    public function a_book_can_be_checked_in()
    {    
        $book = factory(Book::class)->create();       
        $user = factory(User::class)->create();

        $book->checkout( $user );
        $book->checkin( $user );

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals(now(), Reservation::first()->checkout_at);
        $this->assertEquals(now(), Reservation::first()->checkin_at);
    }

    /**
     * A basic unit test example.
     *
     * @test void
     */
    public function a_book_can_be_borrowed_twice()
    {    
        $book = factory(Book::class)->create();       
        $user = factory(User::class)->create();

        $book->checkout( $user );
        $book->checkin( $user );

        $book->checkout( $user );

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertNull(Reservation::find(2)->checkin_at);
        $this->assertEquals(now(), Reservation::find(2)->checkout_at);
        

        $book->checkin( $user );
        $this->assertCount(2, Reservation::all());
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertNotNull(Reservation::find(2)->checkin_at);
        $this->assertEquals(now(), Reservation::find(2)->checkin_at);
    }


    /**
     * A basic unit test example.
     *
     * @test void
     */
    public function if_not_checkout_throw_exception()
    {     
        $this->expectException(\Exception::class);

        $book = factory(Book::class)->create();       
        $user = factory(User::class)->create();

        $book->checkin( $user );

    }
}
