<?php

namespace App\Providers;

use App\Billing\BankPaymentGateway;
use App\Billing\CreditPaymentGateway;
use App\Billing\PaymentGatewayContract;
use App\Channel;
use App\Mixin\RequestMixin;
use App\Mixin\BuilderMixin;
use App\PostcardSendingService;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PaymentGatewayContract::class, function($app)//(1)
        {
           
            if(Input::get("credit"))
            {
                return new CreditPaymentGateway('usd');
            }
            else
            {
                return new BankPaymentGateway('usd');
            }
        });

        //Facades
        $this->app->singleton('Postcard', function($app) {//(2)
            return new PostcardSendingService('usa', 4, 6);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        //option 1: Every single view. But only when want to share sth to views, don't share collection from DB like this as it is resouce intensive
        //View::share( 'channels', Channel::orderBy('name')->get() );
        
        //option 2: Granular view (can be used with widcard)
        // View::composer(['channel.*'], function($view){
        //     $view->with('channels', Channel::orderBy('name')->get() );
        // });
        
        //option 3: Dedicated class 
        View::composer(['channel.*'], \App\Http\View\Composers\ChannelsComposer::class);

        //Macro
       
        /**** Option 1:  **********/
        // Request::macro('tldIs', function ($tld) {
        //     return Str::is('*.' . $tld, 'abc.com');
        // });

        // Builder::macro('whenTldMatches', function($tld, $callback) {
        //     if (Request::tldIs($tld)) {
        //         call_user_func($callback->bindTo($this));
        //     }
        //     else{
        //          die;
        //     }
        // });
        
        /**** Macro  **********/
        Request::mixin( new RequestMixin());
        Builder::mixin( new BuilderMixin());
    }
}

/*
Note
 */
//(1): ghi thẳng tên class như vậy "PaymentGatewayContract::class" là để ở đâu đó trong code (ví dụ như controller) sẽ inject class đó (Dependency Injection)
//(2): ghi vậy là để ở đâu đó trong code(ví dụ Controller) sẽ dùng để initialize Service container binding (class) đó bằng "app()->make('Postcard')"

