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

Route::get('/', 'LoginController@main');
Route::post('/login', 'LoginController@login');

// Coordinator Routes
Route::get('/coordinator', 'CoordinatorController@main');
Route::get('/coordinator/supervisor/add', 'CoordinatorController@showSupervisorAdd');
Route::post('/coordinator/supervisor/add', 'CoordinatorController@supervisorAdd');
Route::get('/coordinator/supervisor/edit/', 'CoordinatorController@showSupervisorEdit');
Route::get('/coordinator/supervisor/edit/item', 'CoordinatorController@showSupervisorEditItem');
Route::post('/coordinator/supervisor/edit/item', 'CoordinatorController@supervisorEditItem');

Route::get('/coordinator/worker/add', 'CoordinatorController@showWorkerAdd');
Route::post('/coordinator/worker/add', 'CoordinatorController@workerAdd');
Route::get('/coordinator/worker/edit', 'CoordinatorController@showWorkerEdit');
Route::get('/coordinator/worker/edit/item', 'CoordinatorController@showWorkerEditItem');
Route::post('/coordinator/worker/edit/item', 'CoordinatorController@workerEditItem');

Route::get('/coordinator/payment-periods', 'CoordinatorController@showPaymentPeriods');
Route::get('/coordinator/payment-periods/add', 'CoordinatorController@showPaymentPeriodsAdd');
Route::post('/coordinator/payment-periods/add', 'CoordinatorController@paymentPeriodsAdd');
Route::get('/coordinator/payments/pay', 'CoordinatorController@showPay');
Route::get('/coordinator/payments/pay/selected', 'CoordinatorController@showPaySelected');
Route::get('/coordinator/payments/pay/selected/unsigned', 'CoordinatorController@showPaySelectedUnsigned');
Route::get('/coordinator/payments/pay/selected/unsigned/sign', 'CoordinatorController@paySelectedUnsignedSign');
Route::get('/coordinator/payments/pay/selected/unsigned/remind', 'CoordinatorController@paySelectedUnsignedRemind');
Route::get('/coordinator/payments/payscale', 'CoordinatorController@showPayscale');
Route::post('/coordinator/payments/payscale', 'CoordinatorController@setPayscale');

Route::get('/coordinator/timecards/import', 'CoordinatorController@timecardsImport');
Route::get('/coordinator/timecards/create', 'CoordinatorController@showTimecardsCreate');
Route::post('/coordinator/timecards/create', 'CoordinatorController@timecardsCreate');
Route::get('/coordinator/timecards/active', 'CoordinatorController@showTimecardsActive');
Route::get('/coordinator/timecards/unsigned', 'CoordinatorController@showTimecardsUnsigned');
Route::get('/coordinator/timecards/submitted', 'CoordinatorController@showTimecardsSubmitted');
Route::get('/coordinator/timecards/submitted/return', 'CoordinatorController@timecardsSubmittedReturn');

Route::get('/coordinator/departments', 'CoordinatorController@showDepartments');
Route::get('/coordinator/departments/add', 'CoordinatorController@showDepartmentsAdd');
Route::post('/coordinator/departments/add', 'CoordinatorController@departmentsAdd');
