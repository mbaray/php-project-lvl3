<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UrlCheckController;

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

Route::get('/', [UrlController::class, 'create'])->name('welcome');

Route::resource('urls', UrlController::class)->except([
    'create', 'edit', 'update', 'destroy'
]);

Route::resource('urls.checks', UrlCheckController::class)->only([
    'store'
]);
