<?php

use Illuminate\Support\Facades\Route;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/books', 'App\Http\Controllers\BookController@index');
Route::put('/books', 'App\Http\Controllers\BookController@store');
Route::get('/book/{book}', 'App\Http\Controllers\BookController@show');

Route::get('/authors', 'App\Http\Controllers\AuthorController@index');
Route::put('/authors', 'App\Http\Controllers\AuthorController@store');
Route::get('/author/{author}', 'App\Http\Controllers\AuthorController@show');

