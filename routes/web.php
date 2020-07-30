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

Route::get('question'                           , 'QuestionsController@index');

Route::match(['GET', 'POST'], 'question\store'  , 'QuestionsController@store');
Route::match(['GET', 'POST'], 'question\show'   , 'QuestionsController@show');
Route::match(['GET', 'POST'], 'question\update' , 'QuestionsController@update');
Route::match(['GET', 'POST'], 'question\destroy', 'QuestionsController@destroy');