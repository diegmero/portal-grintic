<div class="w-full p-4">
    @if(!empty($attachments) && count($attachments) > 0)
        <div class="space-y-6">
            @foreach($attachments as $index => $attachment)
                @php
                    $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                    $isPdf = strtolower($extension) === 'pdf';
                    $url = \Storage::url($attachment);
                    $filename = basename($attachment);
                @endphp
                
                <div class="border rounded-lg overflow-hidden bg-white dark:bg-gray-900">
                    {{-- Header with filename --}}
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-b flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if($isPdf)
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $filename }}
                            </span>
                        </div>
                        <a href="{{ $url }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Abrir
                        </a>
                    </div>
                    
                    {{-- Content --}}
                    <div class="p-4">
                        @if($isPdf)
                            <div class="w-full" style="height: 600px;">
                                <iframe src="{{ $url }}" class="w-full h-full border-0 rounded"></iframe>
                            </div>
                        @else
                            <div class="flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded" style="min-height: 400px;">
                                <img src="{{ $url }}" class="max-w-full max-h-[600px] object-contain rounded" alt="{{ $filename }}">
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($index < count($attachments) - 1)
                    <div class="border-t-2 border-dashed border-gray-300 dark:border-gray-700"></div>
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-lg font-medium">No hay archivos adjuntos</p>
        </div>
    @endif
</div>
