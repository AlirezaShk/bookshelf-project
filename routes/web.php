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

Route::get('/', function() {
	return view('welcome');
});

Route::get('/books', 'App\Http\Controllers\BookController@index');
Route::put('/books', 'App\Http\Controllers\BookController@store');
Route::get('/book/{book}', 'App\Http\Controllers\BookController@show');
Route::put('/book/{book}', 'App\Http\Controllers\BookController@update');
Route::delete('/book/{book}', 'App\Http\Controllers\BookController@destroy');

Route::get('/authors', 'App\Http\Controllers\AuthorController@index');
Route::put('/authors', 'App\Http\Controllers\AuthorController@store');
Route::get('/author/{author}', 'App\Http\Controllers\AuthorController@show');
Route::put('/author/{author}', 'App\Http\Controllers\AuthorController@update');
Route::delete('/author/{author}', 'App\Http\Controllers\AuthorController@destroy');

Route::post('/export/{type}', 'App\Http\Controllers\BookController@export')->name('archive-export');