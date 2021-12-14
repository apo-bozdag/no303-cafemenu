<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

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

Route::get('generate', function (){
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('storage:link');
    echo 'ok';
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('posts', PostController::class)->names([
        'index' => 'posts',
        'create' => 'posts.create'
    ]);
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'categories'
    ]);
    Route::get('/category/search', [CategoryController::class, 'search'])->name('category.search');
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
