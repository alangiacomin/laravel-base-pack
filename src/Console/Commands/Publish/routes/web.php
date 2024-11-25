<?php

use Illuminate\Support\Facades\Route;

//Route::controller(App\Http\Controllers\UserController::class)->group(function () {
//    Route::post('/user/login', 'login')->name('user.login');
//    Route::post('/user/logout', 'logout')->name('user.logout');
//    Route::get('/user/loadUser', 'loadUser3')->name('user.loadUser');
//    Route::get('/user/all', 'all')->name('user.all');
//    Route::post('/user/removeRole', 'removeRole')->name('user.removeRole');
//});

//Route::controller(App\Http\Controllers\AdminController::class)
//    ->prefix('/admin')
//    ->group(function () {
//        Route::get('/', 'index')->name('admin.index');
//    });

//Route::get('/admin', function () {
//    return view('home');
//})->middleware(['can:admin_view']);

Route::get('/', function () {
    return view('home');
});

Route::fallback(function () {
    return view('home');
});
