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


Route::get('/tesseract', 'OCRController@getData');
Route::get('/vision', 'VisionController@getTest');
Route::get('/google', 'GoogleController@getData');

// Route::get('/test', function () {
// echo (new TesseractOCR(public_path("offer.jpg")))
//     ->executable('"C:\Program Files (x86)\Tesseract-OCR\tesseract"')
//     ->run();
// });
//Route::get('/google/{projectId?}/{path?}', ['as' => 'google', 'uses' =>'GoogleController@getText']);