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

/*Route::get('/', function () {
    return view('home');
})->name('landing');*/

// Auth routes
Route::get('/login', '\App\Http\Controllers\AuthController@login')->name('login');
Route::post('/login', '\App\Http\Controllers\AuthController@attemptLogin')->name('auth.login');
Route::get('/logout', '\App\Http\Controllers\AuthController@logout')->name('logout');
Route::get('/register', '\App\Http\Controllers\AuthController@register_step_1')->name('register');
Route::post('/register/info', '\App\Http\Controllers\AuthController@register_step_2')->name('register.second');
Route::post('/register', '\App\Http\Controllers\AuthController@newRegistration')->name('auth.register');
Route::get('/forgot-password', '\App\Http\Controllers\AuthController@password')->name('forgot');
Route::get('/reset/request', '\App\Http\Controllers\AuthController@register')->name('reset.request');
Route::post('/reset/password', '\App\Http\Controllers\AuthController@forgotpassword')->name('reset.password');

Route::prefix('/admin')->middleware('admin')->group(function() {
    # Dashboard
    Route::get('/', '\App\Http\Controllers\DashboardController@admin')->name('dashboard.admin');

    Route::resource('/project', \App\Http\Controllers\Admin\ProjectController::class, ['as' => 'admin']);
    Route::resource('/task', \App\Http\Controllers\Admin\TaskController::class, ['as' => 'admin']);
    Route::resource('/assignment', \App\Http\Controllers\Admin\AssignmentController::class, ['as' => 'admin']);

    Route::prefix('/user')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\UserController@index')->name('user.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\UserController@index')->name('user.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\UserController@add')->name('user.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\UserController@store')->name('user.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\UserController@edit')->name('user.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\UserController@update')->name('user.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\UserController@delete')->name('user.delete.admin');
    });


});

Route::middleware('employee')->group(function() {
    # Dashboard
    Route::get('/', '\App\Http\Controllers\DashboardController@employee')->name('dashboard.employee');

    Route::resource('/list', \App\Http\Controllers\Employee\ListController::class, ['as' => 'employee']);
    Route::resource('/task', \App\Http\Controllers\Employee\TaskController::class, ['as' => 'employee']);
    Route::resource('/assignment', \App\Http\Controllers\Employee\AssignmentController::class, ['as' => 'employee']);
});

