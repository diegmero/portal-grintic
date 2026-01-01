<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     * Usamos 'created' en lugar de 'creating' para tener acceso al ID
     */
    public function created(Invoice $invoice): void
    {
        // Solo generar si no tiene número asignado
        if (empty($invoice->invoice_number)) {
            // Usar el ID garantiza unicidad absoluta
            $invoice->invoice_number = sprintf('INV-%d-%05d', now()->year, $invoice->id);
            $invoice->saveQuietly(); // Sin triggear eventos para evitar loop
        }
    }
}
