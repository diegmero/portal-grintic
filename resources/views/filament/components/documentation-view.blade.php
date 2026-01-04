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
    
    @if($record->content)
        <div class="mt-4 mb-6">
            {!! $record->content !!}
        </div>
    @endif
    
    @if($record->attachments && count($record->attachments) > 0)
        <div class="mt-6 border-t pt-4">
            <h3 class="text-lg font-semibold mb-3">Archivos Adjuntos ({{ count($record->attachments) }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($record->attachments as $attachment)
                    @php
                        $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($extension), ['png', 'jpg', 'jpeg']);
                        $url = \Storage::url($attachment);
                    @endphp
                    
                    <div class="border rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        @if($isImage)
                            <a href="{{ $url }}" target="_blank" class="block">
                                <img src="{{ $url }}" alt="Adjunto" class="w-full h-48 object-cover rounded mb-2">
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ basename($attachment) }}</span>
                                </div>
                            </a>
                        @else
                            <a href="{{ $url }}" target="_blank" class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900 rounded flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ basename($attachment) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                                        PDF Document
                                    </p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
