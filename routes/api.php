<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('v1/login', 'API\UserController@login');
Route::post('v1/register', 'API\UserController@register');


Route::group(['middleware' => 'auth:api'], function(){
Route::get('v1/fetch-dashboard-data', 'API\BusinessController@loadDashboard');
Route::post('v1/create-category', 'API\BusinessController@createCategory');
Route::get('v1/fetch-category', 'API\BusinessController@fetchCategory');
Route::post('v1/business/store', 'API\BusinessController@store');
Route::post('v1/business/fetch', 'API\BusinessController@searchStore');
Route::get('v1/business/detail/{id}', 'API\BusinessController@businessDetail');
Route::post('v1/business/rating', 'API\BusinessController@rating');
Route::get('v1/admin/business', 'API\BusinessController@adminBusiness');
Route::post('v1/business/process', 'API\BusinessController@process');
Route::post('v1/business/edit', 'API\BusinessController@update');





});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
