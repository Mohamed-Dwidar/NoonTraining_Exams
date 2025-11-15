<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Auth\UserAuthController@loginForm')->name('user.loginForm');

Route::group(['prefix' => 'admin/users', 'middleware' => ['auth:admin']], function () {
    Route::get('/', 'Admin\UserAdminModuleController@listOfUsers')->name('admin.users.list');
    Route::get('create', 'Admin\UserAdminModuleController@create')->name('admin.user.create');
    Route::post('store', 'Admin\UserAdminModuleController@store')->name('admin.user.store');
    Route::get('edit/{id}', 'Admin\UserAdminModuleController@edit')->name('admin.user.edit');
    Route::post('update', 'Admin\UserAdminModuleController@update')->name('admin.user.update');
    Route::get('delete/{id}', 'Admin\UserAdminModuleController@destroy')->name('admin.user.delete');
});

Route::prefix('user')->group(function () {
    Route::get('/', 'Auth\UserAuthController@loginForm')->name('user.loginForm');
    Route::get('login', 'Auth\UserAuthController@loginForm')->name('user.loginForm');
    Route::post('login', 'Auth\UserAuthController@login')->name('user.loginpost');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:user']], function () {
    // Route::get('dashboard', 'User\UserAuthController@index')->name('user.dashboard');
    Route::get('changePassword', 'Auth\UserAuthController@changePassword')->name('user.changePassword');
    Route::post('updatePassword', 'Auth\UserAuthController@updatePassword')->name('user.updatePassword');
    Route::get('logout', 'Auth\UserAuthController@logout')->name('user.logout');
});
