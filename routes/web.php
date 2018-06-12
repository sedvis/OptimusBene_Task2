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

Route::get('/', 'WeatherController@index');
Route::get('/current', 'WeatherController@current')->name('current');
Route::post('/save', 'WeatherController@save')->name('save');
Route::post('/subscribe', 'WeatherController@subscribe')->name('subscribe');
