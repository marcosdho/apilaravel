<?php

use GuzzleHttp\Middleware;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'LoginController@index');

Route::group(['prefix'=>'q','middleware'=>['tokenvalid']],function(){
    Route::post('/validToken', 'PlayersController@index');
    Route::resource('/team', 'TeamsController');
    Route::resource('/player', 'PlayersController');
});
