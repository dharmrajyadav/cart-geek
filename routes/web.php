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
    return view('product-list');
});

Route::get('/index', function () {
    return view('product-list');
});


//  Route::post('/productInsert','Product@productInsert')->name('images.upload');
Route::post('/addmedia', 'Product@createmedia');
Route::get('/getProduct', 'Product@getProduct');
Route::post('/getRowID', 'Product@getRowID');
Route::post('/getRowIDupdate', 'Product@getRowIDupdate');