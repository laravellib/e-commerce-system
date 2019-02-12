<?php

Route::resource('categories', 'Categories\CategoryController')->only(['index']);