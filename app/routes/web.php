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
Route::get('/uploadTabela', "SpreadsheetController@showUploadForm");
Route::post('/uploadTabela', "SpreadsheetController@uploadSpreadsheet");
Route::get('/downloadTabela', "SpreadsheetController@downloadLast");
Route::get('/drive', "SpreadsheetController@testeDrive");


Route::get('/', "Indicators\IndicatorsController@index");
Route::get('/calculateAll', "Indicators\IndicatorsController@calculateAndSaveAll");
Route::get("/addunits", "Indicators\IndicatorsController@addUnits");
Route::get("/teste", "Indicators\IndicatorsController@calculateIndicador");
Route::get("/units", "Indicators\IndicatorsController@showUnits");

