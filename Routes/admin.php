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
    Route::get('datatable', 'PostController@datatable')
        ->name('datatable');
    Route::get('index', 'PostController@index')
        ->name('index');
    Route::get('create', 'PostController@create')
        ->name('create');
    Route::post('store', 'PostController@store')
        ->name('store');
    Route::get('edit/{id}', 'PostController@edit')
        ->name('edit');
    Route::post('update/{id}', 'PostController@update')
        ->name('update');
    Route::delete('destroy/{id}', 'PostController@destroy')
        ->name('destroy');
    Route::prefix('translations')->as('translation.')->group(function () {
        Route::get('/edit/{id}/{lang}', 'PostController@edit')
            ->name('edit');
        Route::post('/update/{id}/{lang}', 'PostController@update')
            ->name('update');
    });
});
