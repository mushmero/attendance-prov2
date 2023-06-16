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
    return view('welcome');
});

Auth::routes();

Route::get('/auth/check',function(){
    return (Auth::check()) ? True : False;
});

Route::middleware(['web'])->group(static function () {
    Route::middleware(['auth'])->group(static function () {
        Route::middleware('permission')->group(static function(){ 
            Route::namespace('App\Http\Controllers')->group(static function () {
                Route::get('/home', 'HomeController@index')->name('home');
                // enter your route here to use with permissions
                Route::namespace('Module')->group(static function(){
                    Route::prefix('department')->group(static function(){
                        Route::get('/', 'DepartmentsController@index')->name('department');
                        Route::post('/', 'DepartmentsController@store')->name('department.store');
                        Route::get('/create', 'DepartmentsController@create')->name('department.create');
                        Route::get('/{id}/edit', 'DepartmentsController@edit')->name('department.edit');
                        Route::put('/{id}', 'DepartmentsController@update')->name('department.update');
                        Route::get('/{id}/show', 'DepartmentsController@show')->name('department.show');
                        Route::get('/{id}/delete', 'DepartmentsController@destroy')->name('department.delete');
                    });
                    Route::prefix('units')->group(static function(){
                        Route::get('/', 'UnitsController@index')->name('units');
                        Route::post('/', 'UnitsController@store')->name('units.store');
                        Route::get('/create', 'UnitsController@create')->name('units.create');
                        Route::get('/{id}/edit', 'UnitsController@edit')->name('units.edit');
                        Route::put('/{id}', 'UnitsController@update')->name('units.update');
                        Route::get('/{id}/show', 'UnitsController@show')->name('units.show');
                        Route::get('/{id}/delete', 'UnitsController@destroy')->name('units.delete');
                    });
                });
            });
        });
        // enter route here to use without permissions
    });
});