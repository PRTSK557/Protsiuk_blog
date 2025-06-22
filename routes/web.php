<?php

use App\Http\Controllers\Blog\Admin\CategoryController;
use App\Http\Controllers\Blog\Admin\PostController;
use App\Http\Controllers\RestTestController;
use App\Http\Controllers\DiggingDeeperController;  // Додано

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', PostController::class)
    ->except(['show'])
    ->names('blog.admin.posts');

Route::resource('rest', RestTestController::class)->names('restTest');

Route::get('process-video', 'DiggingDeeperController@processVideo')->name('digging_deeper.processVideo');

Route::get('prepare-catalog', 'DiggingDeeperController@prepareCatalog')->name('digging_deeper.prepareCatalog');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

$groupData = [
    'namespace' => 'App\Http\Controllers\Blog\Admin',
    'prefix' => 'admin/blog',
];

Route::group($groupData, function () {
    $methods = ['index', 'edit', 'store', 'update', 'create'];

    // Категорії
    Route::resource('categories', CategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');

    // Пости блогу
    Route::resource('posts', PostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
});

// Додаємо групу маршрутів для digging_deeper
Route::group(['prefix' => 'digging_deeper'], function () {
    Route::get('collections', [DiggingDeeperController::class, 'collections'])
        ->name('digging_deeper.collections');
});
