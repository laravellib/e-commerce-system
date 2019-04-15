<?php

Route::resource('categories', 'Categories\CategoryController')->only(['index']);
Route::resource('products', 'Products\ProductController')->only(['index', 'show']);
Route::resource('addresses', 'Addresses\AddressController')->only(['index', 'store']);
Route::resource('countries', 'Countries\CountryController')->only(['index']);

Route::group([
    'prefix' => 'auth',
    'namespace' => 'Auth',
], function () {
    Route::post('register', 'RegisterController@store')->name('auth.register.store');
    Route::post('login', 'LoginController@store')->name('auth.login.store');
    Route::get('me', 'MeController@index')->name('auth.me.index');
});

Route::get('cart', 'Cart\CartController@index')->name('cart.index');
Route::post('cart', 'Cart\CartController@store')->name('cart.store');
Route::put('cart/{productVariation}', 'Cart\CartController@update')->name('cart.update');
Route::delete('cart/{productVariation}', 'Cart\CartController@destroy')->name('cart.destroy');
