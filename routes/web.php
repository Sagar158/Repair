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

Route::get('/', function () {
    return view('login');
});

Route::get('/clear',function(){
	\Artisan::call('config:cache');
});
Route::get('clear/configss',function(){
    \Artisan::call('db:wipe');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('profile','ProfileController@profile')->name('profile')->middleware('auth');
Route::post('profile/update','ProfileController@update_profile')->name('update.profile')->middleware('auth');


Route::group(['as'=>'admin.','middleware'=>['auth','admin'],'prefix'=>'admin'],function () {
	Route::get('/dashboard', 'AdminController@index')->name('dashboard');

	Route::get('job/print/{id}','JobController@print_receipt')->name('job.print');
	Route::get('job/assign/{id}','JobController@assign_job')->name('job.assign');
    Route::get('job/my_jobs','JobController@my_jobs')->name('job.my_jobs');
    Route::get('staff/update_status/{id}/{status}','StaffController@update_status')->name('staff.update.status');

   Route::post('job/add_parts','JobController@add_parts')->name('job.add_parts');

   Route::post('job/update_status','JobController@update_status')->name('job.update_status');
   Route::post('job/ready_to_dispatch','JobController@ready_to_dispatch')->name('job.ready_to_dispatch');

   Route::post('job/add_comment','JobController@add_comment')->name('job.add_comment');

   Route::post('job/despatch','JobController@despatch')->name('job.despatch');
   Route::resource('customer','CustomerController');
	Route::resource('job','JobController');
	Route::resource('staff','StaffController');


});
