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

# Spreadsheets
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware(\App\Http\Middleware\ForceJson::class)->group(function () {

    Route::prefix('/account/')->middleware('role:' . UserRole::Root)->group(function () {
        Route::post('/delete', 'AccountController@delete');
        Route::post('/update', 'AccountController@update');
        Route::post('/create', 'AccountController@create');
    });


    Route::prefix('/presentation/')->middleware('role:' . UserRole::Screen)->group(function () {
        Route::get('/templates', 'PresentationController@getTemplates');
        Route::prefix('slide')->group(function () {
            Route::get('/', 'PresentationController@getSlides');
            Route::middleware('role:' . UserRole::Statistics)->group(function () {
                Route::get('/create', 'PresentationController@createSlide');
                Route::get('/indicators', 'PresentationController@setIndicators');
                Route::get('/delete', 'PresentationController@deleteSlide');
                Route::get('/order', 'PresentationController@setOrder');
            });

        });

    });

    Route::prefix('/indicators/')->group(function () {
        Route::get('/values', 'IndicatorsController@getLastValues');
        Route::get('/units/{all?}', 'IndicatorsController@getUnits');
        Route::get('/', 'IndicatorsController@getIndicators');
    });
});


Route::get('/planilhas/', 'SpreadsheetController@index');
Route::get('/planilhas/gcallback', 'SpreadsheetController@googleCallback');
Route::get('/planilhas/login', 'SpreadsheetController@googleLogin');
Route::get('/planilhas/logout', 'SpreadsheetController@googleLogout');
Route::post('/planilhas/pickFile', 'SpreadsheetController@pickFile');
Route::get('/planilhas/downloadFromDrive', 'SpreadsheetController@downloadFromDriveWithRedirects');
Route::get('/planilhas/download', 'SpreadsheetController@downloadLast');


Route::get('/', "Indicators\IndicatorsController@index");
Route::get('/calculateAll', "Indicators\IndicatorsController@calculateAndSaveAll");
Route::get("/addunits", "Indicators\IndicatorsController@addUnits");
Route::get("/teste", "Indicators\IndicatorsController@calculateIndicador");
Route::get("/units", "Indicators\IndicatorsController@showUnits");


Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/home', 'HomeController@index')->name('home');

// Front
Route::get('panel', ['as' => 'panel.index', 'uses' => 'PanelController@index']);
Route::get('slider', ['as' => 'slider.index', 'uses' => 'SliderController@index']);
Route::get('settings', ['as' => 'settings.index', 'uses' => 'SettingsController@index']);
Route::get('report', ['as' => 'report.index', 'uses' => 'ReportController@index']);
Route::get('maintenance', ['as' => 'maintenance.index', 'uses' => 'MaintenanceController@index']);