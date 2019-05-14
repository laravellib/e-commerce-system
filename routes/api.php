<?php

Route::resource('categories', 'Categories\CategoryController')->only(['index']);
Route::resource('products', 'Products\ProductController')->only(['index', 'show']);
Route::resource('addresses', 'Addresses\AddressController')->only(['index', 'store']);
Route::resource('countries', 'Countries\CountryController')->only(['index']);
Route::resource('orders', 'Orders\OrderController')->only(['store', 'index']);
Route::resource('payment-methods', 'PaymentMethods\PaymentMethodController')->only(['index']);

Route::group([
    'prefix' => 'auth',
    'namespace' => 'Auth',
], function () {
    Route::post('register', 'RegisterController@store')->name('auth.register.store');
    Route::post('login', 'LoginController@store')->name('auth.login.store');
    Route::get('me', 'MeController@index')->name('auth.me.index');
});

Route::group([
    'prefix' => 'addresses',
    'namespace' => 'Addresses',
], function () {
    Route::get('{address}/shipping', 'AddressShippingController@index')->name('addresses.shipping.index');
});

Route::group([
    'prefix' => 'cart',
    'namespace' => 'Cart',
], function () {
    Route::get('/', 'CartController@index')->name('cart.index');
    Route::post('/', 'CartController@store')->name('cart.store');
    Route::put('{productVariation}', 'CartController@update')->name('cart.update');
    Route::delete('{productVariation}', 'CartController@destroy')->name('cart.destroy');
});
