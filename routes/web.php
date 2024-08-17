<?php

use App\Http\Controllers\SurveyController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;

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

Route::get('/test', function(){

    $filename = 'a.jpg';

    $img = Image::create(1000, 1000);

    $avatar = Image::read(Storage::disk('public')->path("selfie/01J5CPFR97SVT2R48NFT2KPF6Y.png"));
    $avatar->cover(355, 355);

    $frame = Image::read(resource_path('jadi.png'));
    $frame->cover(1000, 1000);

    $img->place($avatar, 'center', 0, -230);
    $img->place($frame);

    $img->text('ALISSA', 350 + 150, 600 - 0, function (FontFactory $font) {
        $font->filename(resource_path('arial.ttf'));
        $font->size(40);
        $font->color('fff');
        $font->align('center');
        $font->lineHeight(1.6);
    });
    
    $img->text('Alumni TKJ 2 - Tahun 2024', 350 + 150, 650 - 10, function (FontFactory $font) {
        $font->filename(resource_path('arial.ttf'));
        $font->size(30);
        $font->color('fff');
        $font->align('center');
        $font->lineHeight(1.6);
    });
    
    $img->text('Mengisi: 17 Agustus 2024 pada 08:00', 350 + 150, 700 - 10, function (FontFactory $font) {
        $font->filename(resource_path('arial.ttf'));
        $font->size(25);
        $font->color('fff');
        $font->align('center');
        $font->lineHeight(1.6);
    });


    $hasil = $img->toJpeg()->toDataUri();
    $hasil = $img->save(Storage::path($filename));


});