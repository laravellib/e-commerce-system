<?php

Route::get('/', function () {
    $categories = \App\Models\Category::parent()->ordered()->get();

    return $categories;
});
