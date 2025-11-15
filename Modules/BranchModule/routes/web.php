<?php

use Illuminate\Support\Facades\Route;
use Modules\BranchModule\app\Http\Controllers\BranchModuleController;

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
 
Route::group(['prefix' => 'admin/branchs', 'middleware' => ['auth:admin']], function () {

    Route::get('/', 'Admin\BranchAdminController@index')->name('admin.branchs');
    Route::get('/add', 'Admin\BranchAdminController@create')->name('admin.branchs.add');
    Route::post('/store', 'Admin\BranchAdminController@store')->name('admin.branchs.store');
    Route::get('/view/{id}', 'Admin\BranchAdminController@show')->name('admin.branchs.view');
    Route::get('/edit/{id}', 'Admin\BranchAdminController@edit')->name('admin.branchs.edit');
    Route::post('/update', 'Admin\BranchAdminController@update')->name('admin.branchs.update');
    Route::get('/delete/{id}', 'Admin\BranchAdminController@destroy')->name('admin.branchs.delete');
});
