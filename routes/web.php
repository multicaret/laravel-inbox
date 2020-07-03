<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'InboxController@index')->name('inbox.index');
Route::get('create', 'InboxController@create')->name('inbox.create');
Route::post('store', 'InboxController@store')->name('inbox.store');
Route::post('{thread}/reply', 'InboxController@reply')->name('inbox.reply');
Route::get('{thread}', 'InboxController@show')->name('inbox.show');
Route::delete('{thread}/destroy', 'InboxController@destroy')->name('inbox.destroy');