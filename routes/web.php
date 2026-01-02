<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas protegidas para archivos
Route::middleware(['auth'])->group(function () {
    Route::get('/files/payments/{filename}', [FileController::class, 'viewPaymentAttachment'])
        ->name('files.payments.view');
    Route::get('/files/payments/{filename}/download', [FileController::class, 'downloadPaymentAttachment'])
        ->name('files.payments.download');
});
