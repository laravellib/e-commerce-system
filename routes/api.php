<?php

Route::resource('categories', 'Categories\CategoryController')->only(['index']);
Route::resource('products', 'Products\ProductController')->only(['index', 'show']);

Route::group([
    'prefix' => 'auth',
    'namespace' => 'Auth',
], function () {
    Route::post('register', 'RegisterController@store')->name('auth.register.store');
});
