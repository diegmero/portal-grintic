<div class="p-4">
    @if($record->attachments && count($record->attachments) > 0)
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
                            <img src="{{ $url }}" alt="Adjunto" class="w-full h-64 object-contain rounded mb-2 bg-gray-100 dark:bg-gray-900">
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="truncate">{{ basename($attachment) }}</span>
                            </div>
                        </a>
                    @else
                        <a href="{{ $url }}" target="_blank" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900 rounded hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex-shrink-0 w-16 h-16 bg-red-100 dark:bg-red-900 rounded flex items-center justify-center">
                                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No hay archivos adjuntos</p>
    @endif
</div>
