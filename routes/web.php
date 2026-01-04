<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas protegidas para archivos
// Rutas protegidas para archivos (Admins y Clientes)
Route::middleware(['auth:web,client'])->group(function () {
    Route::get('/files/payments/{filename}', [FileController::class, 'viewPaymentAttachment'])
        ->name('files.payments.view');
    Route::get('/files/payments/{filename}/download', [FileController::class, 'downloadPaymentAttachment'])
        ->name('files.payments.download');
    
    Route::get('/files/projects/documentation/{filename}', [FileController::class, 'viewProjectDocumentation'])
        ->name('files.projects.documentation.view');
});
