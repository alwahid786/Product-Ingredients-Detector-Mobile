<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
// Route::get('/admin', function () {
//     return view('add-tag');
// });
Route::any('add-tags', [AuthController::class, 'addTags'])->name('addTags');
Route::any('delete-tag', [AuthController::class, 'deleteTag'])->name('deleteTag');
Route::any('show-survey', [AuthController::class, 'showSurvey'])->name('showSurvey');
Route::any('survey-detail/{id}', [AuthController::class, 'showDetail']);
Route::get('/export-excel', [AuthController::class, 'exporttoexcel'])->name('export-excel');

