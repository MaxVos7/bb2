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
    return view('home');
});


Route::get('users/home', 'HomeController@index')->name('home');
Route::get('tutors/home', 'HomeController@index')->name('home');
Route::get('/lessons', 'LessonController@index');
Route::get('/lessons/{lesson}', 'LessonController@show');

Route::get('/tutors/login', 'Auth\TutorLoginController@showLoginForm')->name('tutors.login');
Route::get('/tutors/register', 'Auth\TutorRegisterController@showRegistrationForm')->name('tutors.register');
Route::post('/tutors/login', 'Auth\TutorLoginController@login');
Route::post('/tutors/register', 'Auth\TutorRegisterController@register');
Route::post('/tutors/logout', 'Auth\LoginController@logout');

Route::group(['prefix' => 'users'], function () {
    Auth::routes(['except' => 'logout']);
});