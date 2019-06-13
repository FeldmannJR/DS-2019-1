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

Route::get('/uploadTabela', "SpreadsheetController@showUploadForm");
Route::post('/uploadTabela', "SpreadsheetController@uploadSpreadsheet");
Route::get('/downloadTabela', "SpreadsheetController@downloadLast");
Route::get('/drive', "SpreadsheetController@downloadPlanilha");
Route::get('/docs', "SpreadsheetController@testeDocs");


Route::get('/planilhas/', 'SpreadsheetController@index');
Route::get('/planilhas/gcallback', 'SpreadsheetController@googleCallback');
Route::get('/planilhas/gcallback', 'SpreadsheetController@googleCallback');
Route::get('/planilhas/glogin', 'SpreadsheetController@googleLogin');
Route::post('/planilhas/pickFile', 'SpreadsheetController@pickFile');
Route::get('/planilhas/download', 'SpreadsheetController@downloadSelected');



Route::get('/', "Indicators\IndicatorsController@index");
Route::get('/calculateAll', "Indicators\IndicatorsController@calculateAndSaveAll");
Route::get("/addunits", "Indicators\IndicatorsController@addUnits");
Route::get("/teste", "Indicators\IndicatorsController@calculateIndicador");
Route::get("/units", "Indicators\IndicatorsController@showUnits");


Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::get('/home', 'HomeController@index')->name('home');
