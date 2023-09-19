<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;

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

// Route::post('/search/product', [ProductController::class, 'searchProduct']);
Route::post('/ingredients', [ProductController::class, 'getIngredients']);
Route::post('/history', [ProductController::class, 'history']);
// Add restricted tag Admin
Route::post('/add/tag', [ProductController::class, 'addTag']);
Route::post('/test_curl', [ProductController::class, 'testCurl']);
Route::post('/beauty_bay', [ProductController::class, 'beautyBay']);
Route::post('/add_survey', [ProductController::class, 'addSurvey']);

// search through barcode
Route::post('/barcodelookup', [ProductController::class, 'barcodelookup']);
