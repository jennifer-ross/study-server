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

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//})->middleware(\App\Http\Middleware\VerifyToken::class);

//Route::get('api/token', 'Api\TokenController@TokenHandler');

Route::get('api/verifyClientId', 'Api\verifyClientId@ClientIdHandler');


// ==== ACCOUNT ===={
Route::get('api/account/login', 'Api\AccountLogRegController@AccountLoginHandler');

Route::get('api/account/register', 'Api\AccountLogRegController@AccountRegisterHandler');

Route::get('api/account/logout', 'Api\AccountController@AccountLogOutHandler')->middleware(\App\Http\Middleware\VerifyToken::class);

Route::get('api/account/change/password', 'Api\AccountController@AccountChangePasswordHandler')->middleware(\App\Http\Middleware\VerifyToken::class);

Route::get('api/account/update', 'Api\AccountController@AccountUpdateFieldsHandler')->middleware(\App\Http\Middleware\VerifyToken::class);
// }==== ACCOUNT ====

// ==== IMAGE ===={
Route::post('api/image/create', 'Api\ImageController@ImageCreateHandler')->middleware(\App\Http\Middleware\VerifyToken::class);
// }==== IMAGE ====

// ==== LEARNERS ===={
Route::get('api/learners/get', 'Api\LearnersController@LearnersGetHandler')->middleware(\App\Http\Middleware\VerifyToken::class);

Route::get('api/learners/count', 'Api\LearnersController@LearnersMaxCountHandler')->middleware(\App\Http\Middleware\VerifyToken::class);

Route::post('api/learners/create', 'Api\LearnersController@LearnerCreateHandler')->middleware(\App\Http\Middleware\VerifyToken::class);

Route::get('api/learners/search', 'Api\LearnersController@LearnerSearchHandler')->middleware(\App\Http\Middleware\VerifyToken::class);

Route::get('api/learners/remove', 'Api\LearnersController@LearnerRemoveHandler')->middleware(\App\Http\Middleware\VerifyToken::class);

Route::post('api/learners/update', 'Api\LearnersController@LearnerUpdateFieldsHandler')->middleware(\App\Http\Middleware\VerifyToken::class);
// }==== LEARNERS ====