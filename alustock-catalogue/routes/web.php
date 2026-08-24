<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/gammes', [\App\Http\Controllers\Public\GammeController::class, 'index'])->name('gammes.index');
Route::get('/gammes/{gamme}', [\App\Http\Controllers\Public\GammeController::class, 'show'])->name('gammes.show');
Route::get('/gammes/{gamme}/ouvrages', [\App\Http\Controllers\Public\GammeController::class, 'ouvrages'])->name('gammes.ouvrages');
Route::get('/gammes/{gamme}/composants', [\App\Http\Controllers\Public\GammeController::class, 'composants'])->name('gammes.composants');


