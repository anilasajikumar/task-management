<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TallyController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/fetch-sales', [TallyController::class, 'fetchSales']);
// Route::post('/insert-sales', [TallyController::class, 'insertSales']);
// Route::get('/fetch-sales', [TallyController::class, 'fetchSalesData']);


Route::get('/tally/form', [TallyController::class, 'showForm'])->name('tally.form');
Route::post('/tally/insert', [TallyController::class, 'insertEntry'])->name('tally.insert');
Route::get('/tally/fetch', [TallyController::class, 'fetchData'])->name('tally.fetch');
