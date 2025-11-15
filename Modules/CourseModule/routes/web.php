<?php

use Illuminate\Support\Facades\Route;
use Modules\CourseModule\app\Http\Controllers\CourseModuleController;

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

Route::group(['prefix' => 'admin/courses', 'middleware' => ['auth:admin']], function () {

    Route::get('/', 'Admin\CourseAdminController@index')->name('admin.courses');
    Route::get('/add', 'Admin\CourseAdminController@create')->name('admin.courses.add');
    Route::post('/store', 'Admin\CourseAdminController@store')->name('admin.courses.store');
    Route::get('/show/{id}', 'Admin\CourseAdminController@show')->name('admin.courses.show');
    Route::get('/edit/{id}', 'Admin\CourseAdminController@edit')->name('admin.courses.edit');
    Route::post('/update', 'Admin\CourseAdminController@update')->name('admin.courses.update');
    Route::get('/delete/{id}', 'Admin\CourseAdminController@destroy')->name('admin.courses.delete');

    Route::get('/registers', 'Admin\CourseAdminController@registers')->name('admin.courses.registers');
    Route::get('/courseRegisters/{id}', 'Admin\CourseAdminController@courseRegisters')->name('admin.courses.courseRegisters');
    Route::post('/regAction', 'Admin\CourseAdminController@regAction')->name('admin.courses.reg_action');
    Route::get('/deleteReg/{id}', 'Admin\CourseAdminController@destroyReg')->name('admin.courses.delete_reg');

    Route::post('/updateRegStatus', 'Admin\CourseAdminController@updateRegStatus')->name('admin.courses.updateRegStatus');
    Route::post('/updatePaymentType', 'Admin\CourseAdminController@updatePaymentType')->name('admin.courses.updatePaymentType');

    Route::post('/payAction', 'Admin\CourseAdminController@payAction')->name('admin.courses.pay_action');
    Route::post('/receiptAction', 'Admin\CourseAdminController@receiptAction')->name('admin.courses.receipt_action');

    Route::get('/setCertDelivered/{id}', 'Admin\CourseAdminController@setCertDelivered')->name('admin.courses.set_cert_as_delivered');
    Route::get('/setCertNotDelivered/{id}', 'Admin\CourseAdminController@setCertNotDelivered')->name('admin.courses.set_cert_as_not_delivered');

    Route::post('/print', 'Admin\CourseAdminController@genReceiptPDF')->name('admin.Receipt.gen_pdf');
    Route::post('/changeprice', 'Admin\CourseAdminController@ChangePriceForOneStudent')->name('admin.ChangePriceForOneStudent');
    Route::post('/UpdateDiscountForOneStudent', 'Admin\CourseAdminController@UpdateDiscountForOneStudent')->name('admin.UpdateDiscountForOneStudent');
    Route::post('/changeExamprice', 'Admin\CourseAdminController@ChangeExamPriceForOneStudent')->name('admin.ChangeExamPriceForOneStudent');

    Route::post('/updateRegBy', 'Admin\CourseAdminController@UpdateRegBy')->name('admin.updateRegBy');

    Route::post('assignStudent', 'Admin\CourseAdminController@assignStudentToCourse')->name('admin.courses.assign_student');
});

Route::group(['prefix' => 'user/courses', 'middleware' => ['auth:user']], function () {
    Route::get('/', 'User\CourseUserController@index')->name('user.courses');
    Route::get('/add', 'User\CourseUserController@create')->name('user.courses.add');
    Route::post('/store', 'User\CourseUserController@store')->name('user.courses.store');
    Route::get('/show/{id}', 'User\CourseUserController@show')->name('user.courses.show');
    Route::get('/edit/{id}', 'User\CourseUserController@edit')->name('user.courses.edit');
    Route::post('/update', 'User\CourseUserController@update')->name('user.courses.update');
    Route::get('/delete/{id}', 'User\CourseUserController@destroy')->name('user.courses.delete');

    Route::get('/registers', 'User\CourseUserController@registers')->name('user.courses.registers');
    Route::get('/courseRegisters/{id}', 'User\CourseUserController@courseRegisters')->name('user.courses.courseRegisters');
    Route::post('/regAction', 'User\CourseUserController@regAction')->name('user.courses.reg_action');
    Route::get('/deleteReg/{id}', 'User\CourseUserController@destroyReg')->name('user.courses.delete_reg');

    Route::post('/updateRegStatus', 'User\CourseUserController@updateRegStatus')->name('user.courses.updateRegStatus');
    Route::post('/updatePaymentType', 'User\CourseUserController@updatePaymentType')->name('user.courses.updatePaymentType');

    Route::post('/payAction', 'User\CourseUserController@payAction')->name('user.courses.pay_action');
    Route::post('/receiptAction', 'User\CourseUserController@receiptAction')->name('user.courses.receipt_action');

    Route::get('/setCertDelivered/{id}', 'User\CourseUserController@setCertDelivered')->name('user.courses.set_cert_as_delivered');
    Route::get('/setCertNotDelivered/{id}', 'User\CourseUserController@setCertNotDelivered')->name('user.courses.set_cert_as_not_delivered');

    Route::post('/print', 'User\CourseUserController@genReceiptPDF')->name('user.Receipt.gen_pdf');
    Route::post('/changeprice', 'User\CourseUserController@ChangePriceForOneStudent')->name('user.ChangePriceForOneStudent');
    Route::post('/UpdateDiscountForOneStudent', 'User\CourseUserController@UpdateDiscountForOneStudent')->name('user.UpdateDiscountForOneStudent');
    Route::post('/changeExamprice', 'User\CourseUserController@ChangeExamPriceForOneStudent')->name('user.ChangeExamPriceForOneStudent');

    Route::post('/updateRegBy', 'User\CourseUserController@UpdateRegBy')->name('user.updateRegBy');


    Route::post('assignStudent', 'User\CourseUserController@assignStudentToCourse')->name('user.courses.assign_student');
});



Route::group(['prefix' => 'admin/unknown_payment', 'middleware' => ['auth:admin']], function () {
    Route::get('/', 'Admin\UnknownPaymentAdminController@index')->name('admin.unknown_payment.list');
    Route::get('/add', 'Admin\UnknownPaymentAdminController@create')->name('admin.unknown_payment.add');
    Route::post('/store', 'Admin\UnknownPaymentAdminController@store')->name('admin.unknown_payment.store');
    Route::get('/edit/{id}', 'Admin\UnknownPaymentAdminController@edit')->name('admin.unknown_payment.edit');
    Route::post('/update', 'Admin\UnknownPaymentAdminController@update')->name('admin.unknown_payment.update');
    Route::get('/delete/{id}', 'Admin\UnknownPaymentAdminController@destroy')->name('admin.unknown_payment.delete');
    Route::post('/assignPayment', 'Admin\UnknownPaymentAdminController@assignPayment')->name('admin.unknown_payment.assign_payment');
    Route::get('/assignToReg/{id}', 'Admin\UnknownPaymentAdminController@assignToReg')->name('admin.unknown_payment.assign');
    Route::get('/get-students/{courseId}', 'Admin\UnknownPaymentAdminController@getStudentsForCourse');
});

Route::group(['prefix' => 'user/unknown_payment', 'middleware' => ['auth:user']], function () {
    Route::get('/', 'User\UnknownPaymentUserController@index')->name('user.unknown_payment.list');
    Route::get('/add', 'User\UnknownPaymentUserController@create')->name('user.unknown_payment.add');
    Route::post('/store', 'User\UnknownPaymentUserController@store')->name('user.unknown_payment.store');
    Route::get('/edit/{id}', 'User\UnknownPaymentUserController@edit')->name('user.unknown_payment.edit');
    Route::post('/update', 'User\UnknownPaymentUserController@update')->name('user.unknown_payment.update');
    Route::get('/delete/{id}', 'User\UnknownPaymentUserController@destroy')->name('user.unknown_payment.delete');
    Route::post('/assignPayment', 'User\UnknownPaymentUserController@assignPayment')->name('user.unknown_payment.assign_payment');
    Route::get('/assignToReg/{id}', 'User\UnknownPaymentUserController@assignToReg')->name('user.unknown_payment.assign');
    Route::get('/get-students/{courseId}', 'User\UnknownPaymentUserController@getStudentsForCourse');
});
