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

Route::get('/', function () {
    return view('welcome');
});

//Book
Route::prefix('book')->name('book.')->group(function () {
	Route::get('new', 'App\Http\Controllers\BookController@create')->name('create-view');
	Route::put('new', 'App\Http\Controllers\BookController@store')->name('create');

	Route::get('list', 'App\Http\Controllers\BookController@index')->name('list');

	Route::get('b:{book}', 'App\Http\Controllers\BookController@show')->name('entry/edit-view');
	Route::put('b:{book}', 'App\Http\Controllers\BookController@update')->name('edit');
	Route::delete('b:{book}', 'App\Http\Controllers\BookController@destroy')->name('delete');

	Route::post('export/{type}', 'App\Http\Controllers\BookController@export')->name('export');
});

//Author
Route::prefix('author')->name('author.')->group(function () {
	Route::get('new', 'App\Http\Controllers\AuthorController@create')->name('create-view');
	Route::put('new', 'App\Http\Controllers\AuthorController@store')->name('create');

	Route::get('list', 'App\Http\Controllers\AuthorController@index')->name('list');

	Route::get('a:{author}', 'App\Http\Controllers\AuthorController@show')->name('entry/edit-view');
	Route::put('a:{author}', 'App\Http\Controllers\AuthorController@update')->name('edit');
	Route::delete('a:{author}', 'App\Http\Controllers\AuthorController@destroy')->name('delete');


	Route::post('export/{type}', 'App\Http\Controllers\AuthorController@export')->name('export');
});

//API
Route::prefix('api')->name('api.')->middleware('api_handle')->group(function () {
    Route::prefix('book')->name('book.')->group(function () {
        Route::get('isbn-sample', 'App\Http\Controllers\BookController@exampleISBN')->name('isbn-sample');

	Route::get('b:{book}', 'App\Http\Controllers\BookController@show')->name('entry/edit-view');
	Route::put('b:{book}', 'App\Http\Controllers\BookController@update')->name('edit');
	Route::delete('b:{book}', 'App\Http\Controllers\BookController@destroy')->name('delete');

        Route::put('filter', 'App\Http\Controllers\BookController@applyFilter')->name('apply-filter');
        Route::delete('filter', 'App\Http\Controllers\BookController@deleteFilter')->name('delete-filter');
    });
    Route::prefix('author')->name('author.')->group(function () {
        Route::get('aid-sample', 'App\Http\Controllers\AuthorController@exampleAuthorId')->name('aid-sample');

	Route::get('a:{author}', 'App\Http\Controllers\AuthorController@show')->name('entry/edit-view');
	Route::put('a:{author}', 'App\Http\Controllers\AuthorController@update')->name('edit');
	Route::delete('a:{author}', 'App\Http\Controllers\AuthorController@destroy')->name('delete');
        Route::put('filter', 'App\Http\Controllers\AuthorController@applyFilter')->name('apply-filter');
        Route::delete('filter', 'App\Http\Controllers\AuthorController@deleteFilter')->name('delete-filter');
    });
});
