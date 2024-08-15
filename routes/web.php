<?php

use App\Http\Controllers\SurveyController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use MattDaneshvar\Survey\Models\Survey;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest:student')->group(function(){
    Route::get('/', [WelcomeController::class, 'index'])->name('login');
    Route::post('/', [WelcomeController::class, 'login']);
});

Route::middleware('auth:student')->group(function() {

    Route::get('/home', [SurveyController::class, 'index'])->name('survey.show');
    Route::post('/home', [SurveyController::class, 'save']);

});