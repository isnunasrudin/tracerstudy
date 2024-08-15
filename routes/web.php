<?php

use App\Http\Controllers\SurveyController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use MattDaneshvar\Survey\Models\Survey;
use Intervention\Image\Laravel\Facades\Image;

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

// Route::get('/test', function(){

//     $path = 'a.jpg';

//     $img = Image::create(500, 500);

//     $avatar = Image::read(Storage::disk('public')->path("selfie/4wWtTpkpxgHH9EYnJzsKU2SQR64xcruZ1Yx7WeHj.jpg"));
//     $avatar->cover(180, 180);

//     $frame = Image::read(resource_path('jadi.png'));
//     $frame->cover(500, 500);

//     $img->place($avatar, 'center', 0, -110);
//     $img->place($frame);

//     $img->save(Storage::path('a.jpg'), progressive: true);

// });