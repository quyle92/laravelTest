<?php

namespace Tests\Feature;

use App\Book;
use App\Author;
use App\Reservation;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookCheckOutTest extends TestCase
{   
    use RefreshDatabase;
    /**
     * @test void
     */
    public function a_book_can_be_checked_out_by_signed_in_user()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $response = $this->actingAs($user)->post('/checkout/', [
            'book_id' => $book->id,
        ]);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals(1, Reservation::first()->book_id);
        $this->assertEquals(1, Reservation::first()->user_id);

    }

    /**
     * @test void
     */
    public function only_signed_in_users_can_checkout_a_book()
    {
        //$this->withoutExceptionHandling();(1)
        $book = factory(Book::class)->create();

        $response = $this->post('/checkout/', [
            'book_id' => $book->id,
        ])->assertRedirect('/login');
        
        $this->assertGuest($guard = null);
        $this->assertCount(0, Reservation::all());
    }

    /**
     * @test void
     */
    public function only_real_book_can_be_checkout()
    {
        //$this->withoutExceptionHandling();(1)
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->post('/checkout/', [
            'book_id' => 123,
        ]);

        $this->assertCount(0, Reservation::all());
    }

    /**
     * @test void
     */
    public function a_book_can_be_checked_in_by_signed_in_user()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $this->actingAs($user)->post('/checkout/', [
            'book_id' => $book->id,
        ]);

        $this->actingAs($user)->post('/checkin/', [
            'book_id' => $book->id,
        ]);

        $this->assertCount(1, Reservation::all());
    }

    /**
     * @test void
     */
    public function only_signed_in_users_can_checkin_a_book()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user )->post('/checkout/', [
            'book_id' => $book->id,
        ]);

        Auth::logout();
        $response = $this->post('/checkin/', [
            'book_id' => $book->id,
        ]);
        
        $this->assertGuest($guard = null);
        $this->assertNull(Reservation::first()->checkin_at);
    }

    /**
     * @test void
     */
    public function only_real_book_can_be_checkin()
    {
        // $this->expectException(ModelNotFoundException::class);//(2)
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $response = $this->actingAs($user)->post('/checkout/', [
            'book_id' => $book->id,
        ]);

        $response = $this->post('/checkin/', [
            'book_id' => 123,
        ]);

       
        $this->assertNull(Reservation::first()->checkin_at);
    }

    /**
     * @test void
     */
    public function exception_is_thrown_if_book_is_not_checkout_first()
    {
        $user = factory(User::class)->create();
        $book = factory(Book::class)->create();

        $response = $this->post('/checkin/', [
            'book_id' => 123,
        ]);

        $this->assertCount(0, Reservation::all());
    }


}

/*
Note
 */
//(1):$this->withoutExceptionHandling() ko đc dùng khi test những case mà mình muốn chủ động nó sai (VD như validation, unauthenticated) but not for exception handling like in (2), ko thì nó sẽc đúng cái sai đó và ko báo xanh.(ref: https://laracasts.com/discuss/channels/testing/failed-asserting-that-exception-of-type-exception-is-thrown (last comment))

