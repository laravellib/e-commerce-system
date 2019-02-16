<?php

Route::resource('categories', 'Categories\CategoryController')->only(['index']);
Route::resource('products', 'Products\ProductController')->only(['index']);