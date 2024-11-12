<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TallyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fetch-sales', [TallyController::class, 'fetchSales']);
Route::post('/insert-sales', [TallyController::class, 'insertSales']);
Route::get('/fetch-sales', [TallyController::class, 'fetchSalesData']);

