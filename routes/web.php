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
Route::get('/book/new', 'App\Http\Controllers\BookController@create');
Route::get('/book/edit/{book}', 'App\Http\Controllers\BookController@edit');
Route::put('/books/filter', 'App\Http\Controllers\BookController@applyFilter');
Route::delete('/books/filter', 'App\Http\Controllers\BookController@deleteFilter');
Route::delete('/book/{book}', 'App\Http\Controllers\BookController@destroy');

Route::get('/authors', 'App\Http\Controllers\AuthorController@index');
Route::put('/authors', 'App\Http\Controllers\AuthorController@store');
Route::get('/author/{author}', 'App\Http\Controllers\AuthorController@show');
Route::put('/author/{author}', 'App\Http\Controllers\AuthorController@update');
Route::get('/author/new', 'App\Http\Controllers\AuthorController@create');
Route::get('/author/edit/{author}', 'App\Http\Controllers\AuthorController@edit');
Route::delete('/author/{author}', 'App\Http\Controllers\AuthorController@destroy');

Route::post('/export/{type}', 'App\Http\Controllers\BookController@export')->name('archive-export');