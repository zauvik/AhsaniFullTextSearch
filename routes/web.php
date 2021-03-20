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
//skripsi

Route::get('/', 'SkripsiController@index')->name('skripsi');
// Route::post('/check', 'SkripsiController@mainProcess')->name('skripsi.check');
Route::post('/action','SkripsiController@gateway')->name('skripsi.gateway');
// Route::post('skripsi/insert', 'SkripsiController@insert')->name('skripsi.insert');

//profile
Route::get('profile', 'ProfileController@index')->name('profile');