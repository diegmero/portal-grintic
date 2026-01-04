<div class="prose dark:prose-invert max-w-none">
    <div class="mb-4">
        <span class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $record->category->getColor() }}">
            {{ $record->category->getLabel() }}
        </span>
    </div>
    
    <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
        <strong>Autor:</strong> {{ $record->user?->name ?? 'Sistema' }} | 
        <strong>Última actualización:</strong> {{ $record->updated_at->timezone('America/Bogota')->format('d/m/Y h:i A') }}
    </div>
    
    <div class="mt-4">
        {!! $record->content !!}
    </div>
</div>
