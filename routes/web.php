<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', fn() => view('pages.home'))->name('home');
Route::get('/servicios', fn() => view('pages.servicios'))->name('servicios');
Route::get('/nosotros', fn() => view('pages.nosotros'))->name('nosotros');
Route::get('/contacto', fn() => view('pages.contacto'))->name('contacto');

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
