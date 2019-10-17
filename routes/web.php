<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/session/{id}/details', 'HomeController@ajaxSessionDetails')->name('sessions.details');
Route::delete('/bookings/{id}', 'HomeController@deleteBooking')->name('bookings.delete');
Route::post('/bookings', 'HomeController@createBooking')->name('bookings.create');
