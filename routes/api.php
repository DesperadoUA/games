<?php

use Illuminate\Http\Request;

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

Route::namespace('Api')->group(function () {

	Route::post('login', 'LoginController@index');
	Route::post('logout', 'LoginController@logout');

	Route::post('admin/uploads', 'AdminUploadsController@index')->middleware('api_auth');
	Route::post('admin/media', 'AdminUploadsController@media')->middleware('api_auth');
	Route::post('admin/delete-media', 'AdminUploadsController@delete')->middleware('api_auth');

	Route::post('admin/options', 'AdminOptionsController@index')->middleware('api_auth');
	Route::post('admin/options/update', 'AdminOptionsController@update')->middleware('api_auth');
	Route::post('admin/options/{id}', 'AdminOptionsController@show')->middleware('api_auth');

	Route::post('admin/settings', 'AdminSettingsController@index')->middleware('api_auth');
	Route::post('admin/settings/update', 'AdminSettingsController@update')->middleware('api_auth');
	Route::post('admin/settings/{id}', 'AdminSettingsController@show')->middleware('api_auth');

	Route::post('admin/pages', 'AdminPageController@index')->middleware('api_auth');
	Route::post('admin/pages/update', 'AdminPageController@update')->middleware('api_auth');
	Route::post('admin/pages/{id}', 'AdminPageController@show')->middleware('api_auth');

	Route::post('admin/category', 'AdminCategoryController@index')->middleware('api_auth');
	Route::post('admin/category/update', 'AdminCategoryController@update')->middleware('api_auth');
	Route::post('admin/category/{id}', 'AdminCategoryController@show')->middleware('api_auth');

	Route::post('admin/search', 'AdminSearchController@index')->middleware('api_auth');

	//------ Post types ------//

	Route::post('admin/emulators', 'AdminEmulatorsController@index')->middleware('api_auth');
	Route::post('admin/emulators/update', 'AdminEmulatorsController@update')->middleware('api_auth');
	Route::post('admin/emulators/delete', 'AdminEmulatorsController@delete')->middleware('api_auth');
	Route::post('admin/emulators/store', 'AdminEmulatorsController@store')->middleware('api_auth');
	Route::post('admin/emulators/{id}', 'AdminEmulatorsController@show')->middleware('api_auth');

	Route::post('admin/roms', 'AdminRomsController@index')->middleware('api_auth');
	Route::post('admin/roms/update', 'AdminRomsController@update')->middleware('api_auth');
	Route::post('admin/roms/delete', 'AdminRomsController@delete')->middleware('api_auth');
	Route::post('admin/roms/store', 'AdminRomsController@store')->middleware('api_auth');
	Route::post('admin/roms/{id}', 'AdminRomsController@show')->middleware('api_auth');

	//----- Front controllers ----//
	Route::get('pages/'.config('constants.PAGES.MAIN.URL'), 'PageController@main');

	Route::get('category/roms', 'CategoryController@roms');
	Route::get('category/emulators', 'CategoryController@emulators');
	Route::get('category/{id}', 'CategoryController@show');

});

