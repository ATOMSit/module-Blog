<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('posts')->as('post.')->group(function () {

    Route::prefix('tags')->as('tag.')->group(function () {
        Route::get('{id}/store', 'PostController@tag');
        Route::get('find', 'PostController@search')->name('find');
    });


    Route::get('datatable', 'PostController@datatable')
        ->name('datatable');
    Route::get('index', 'PostController@index')
        ->name('index');
    Route::get('create', 'PostController@create')
        ->name('create');
    Route::post('store', 'PostController@store')
        ->name('store');


    Route::get('show/{post}', 'PostController@show');

    Route::get('edit/{post}', 'PostController@edit')
        ->name('edit');
    Route::post('update/{post}', 'PostController@update')
        ->name('update');


    Route::delete('destroy/{post}', 'PostController@destroy')
        ->name('destroy');
    Route::prefix('translations')->as('translation.')->group(function () {
        Route::get('/edit/{post}/{lang}', 'PostController@edit')
            ->name('edit');
        Route::post('/update/{post}/{lang}', 'PostController@update')
            ->name('update');
    });
});
