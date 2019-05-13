<?php

namespace App\Providers;

use App\Cart\Cart;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cart::class, function ($app) {
            $user = $app->auth->user();

            if ($user) {
                $user->load(['cart.stock']);
            }

            return new Cart($user);
        });
    }
}
