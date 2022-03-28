<?php
// Class Culture {
// 	public function __construct( $culture = 'culture' ){
// 		$this->culture = $culture;
// 	}
// }

// Class MartialArt {
// 	public function __construct( $country, Culture $culture ){
// 		$this->country = $country;
// 		$this->culture = $culture;
// 	}
// }

Class Karate {
	public function __construct(
        //MartialArt $martialArt
        ){
		//$this->martialArt = $martialArt;
	}
}

Class Kyokushin {
	public function __construct( $weapon, Karate $karate){
		$this->karate = $karate;
		$this->weapon = $weapon;
	}
}
// app()->bind(MartialArt::class, function($app){
// 	$country = "Vietnam";
// 	$culture = resolve(Culture::class);
// 	return new MartialArt($country, $culture);
// });
app()->bind('Kyokushin', function($app){
	$weapon = "nunchaku";
	return new Kyokushin( $weapon,  app('Karate'));
});
// dd(app('Kyokushin'));
// app()->bind('vo_thuat', function(){
// 	return new Kyokushin(new Karate(new MartialArt));
// });
// app()->bind('thuc_chien', function(){
// 	return new Kyokushin(new Karate(new MartialArt));
// });
//dd(app()->make('thuc_chien'));

//cache()->get('abc');
//dd(app());

use App\Post;
use App\Postcard;
use App\PostcardSendingService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['verify' => true]);

Route::get('/', function () {
    return view('welcome');
});

Route::get('image-upload', 'ImageUploadController@imageUpload')->name('image.upload');

Route::post('image-upload', 'ImageUploadController@imageUploadPost')->name('image.upload.post');

Route::get('event', function () {
    event(new App\Events\StatusLiked('Someone'));
    return "Event has been sent!";
});

Route::get('listener', function () {
    return view('listener');
});




Route::get('axios', function () {
    return view('axios');
});

Route::resource('products', \ProductController::class);

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/welcome', function(){
	return  view('welcome');
})->name('welcome');
Route::get('/test', function(){
	return  view('test');
})->name('test');

/*
Laravel advanced
 */
Route::get('/pay', 'PayOrderController@store');

Route::get('/channels', 'ChannelController@index');
Route::get('/getAllPic', 'ChannelController@getAllPic');

//Laravel 6 Advanced - e4 - Facades
Route::get('/postcard', function(){

	$postcardService = new  PostcardSendingService('usa', 4, 6);

	$postcardService->hello('Hello, I am here to help you... Don\'t worried!', 'doctor@gmail.com');

});

Route::get('/facades', function(){

	Postcard::hello('Hello, I am here to help you... Don\'t worried!', 'doctor@gmail.com');

});

//Laravel 6 Advanced - e5 - Macros
Route::get('/partNumber', function(){

	return User::whenTldMatches('com', function () {//whenTldMatches is from  Builder macro so any Model can call it.
	    $this->where('id', '<', 5);
	})->get();

});

//Laravel 6 Advanced - e6 - Pipeline
Route::get('/pipeline', function(){

	$posts = Post::filterPosts();

    return view('pipeline', compact("posts"));

});


/*
Paypal Ommi
 */
Route::get('/{order?}', [
    'name' => 'PayPal Express Checkout',
    'as' => 'app.home',
    'uses' => 'PayPalController@form',
]);

Route::post('/checkout/payment/{order}/paypal', [
    'name' => 'PayPal Express Checkout',
    'as' => 'checkout.payment.paypal',
    'uses' => 'PayPalController@checkout',
]);

Route::get('/paypal/checkout/{order}/completed', [
    'name' => 'PayPal Express Checkout',
    'as' => 'paypal.checkout.completed',
    'uses' => 'PayPalController@completed',
]);

Route::get('/paypal/checkout/{order}/cancelled', [
    'name' => 'PayPal Express Checkout',
    'as' => 'paypal.checkout.cancelled',
    'uses' => 'PayPalController@cancelled',
]);

Route::post('/webhook/paypal/{order?}/{env?}', [
    'name' => 'PayPal Express IPN',
    'as' => 'webhook.paypal.ipn',
    'uses' => 'PayPalController@webhook',
]);


/*
**Unit test
 */
Route::post('book', 'BookController@store');
Route::patch('book/{book}-{bookSlug}', 'BookController@update');
Route::delete('book/{book}', 'BookController@destroy');
Route::post('author', 'AuthorController@store');
Route::patch('author/{author}', 'AuthorController@update');
Route::delete('author/{author}', 'AuthorController@destroy');
Route::post('checkout', 'CheckoutBookController@store');
Route::post('checkin', 'CheckinBookController@store');


