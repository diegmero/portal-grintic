<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    /**
     * Find the disk and path where the file exists
     */
    private function findFileLocation(string $filename): ?array
    {
        // Try different path combinations
        $pathsToTry = [
            $filename,
            "payments/{$filename}",
            "project-documentation/{$filename}",
        ];
        
        foreach (['local', 'public'] as $disk) {
            foreach ($pathsToTry as $path) {
                if (Storage::disk($disk)->exists($path)) {
                    return ['disk' => $disk, 'path' => $path];
                }
            }
        }
        
        return null;
    }

    /**
     * Descargar comprobante de pago de forma segura
     */
    public function downloadPaymentAttachment(Request $request, $filename): StreamedResponse
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado');
        }

        $payment = Payment::where('attachment_path', 'like', "%{$filename}%")->firstOrFail();

        $location = $this->findFileLocation($filename);
        
        if (!$location) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk($location['disk'])->download($location['path'], $filename, [
            'Content-Type' => Storage::disk($location['disk'])->mimeType($location['path']),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Vista previa de comprobante de pago
     */
    public function viewPaymentAttachment(Request $request, $filename)
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado');
        }

        $payment = Payment::where('attachment_path', 'like', "%{$filename}%")->firstOrFail();

        $location = $this->findFileLocation($filename);
        
        if (!$location) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->file(Storage::disk($location['disk'])->path($location['path']), [
            'Content-Type' => Storage::disk($location['disk'])->mimeType($location['path']),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
    /**
     * Vista previa de documentación de proyecto
     */
    public function viewProjectDocumentation(Request $request, $filename)
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado');
        }

        $location = $this->findFileLocation($filename);
        
        if (!$location) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->file(Storage::disk($location['disk'])->path($location['path']), [
            'Content-Type' => Storage::disk($location['disk'])->mimeType($location['path']),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
