<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuranController;
use App\Http\Controllers\PrayerTimeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PrayerTimeController::class, 'index'])->name('home');
Route::get('/calendar', [PrayerTimeController::class, 'calendar'])->name('calendar');

Route::group(['prefix' => 'quran', 'as' => 'quran.'], function () {
    Route::get('/', [QuranController::class, 'index'])->name('index');
    Route::get('/surah/{surahNumber}', [QuranController::class, 'showSurah'])->name('surah');
    Route::get('/search', [QuranController::class, 'search'])->name('search');
    Route::get('/juz', [QuranController::class, 'juz'])->name('juz');
    Route::get('/juz/{juzNumber}', [QuranController::class, 'showJuz'])->name('juz.detail');
});
