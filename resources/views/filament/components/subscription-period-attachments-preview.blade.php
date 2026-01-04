<div class="w-full p-4">
    @if(!empty($attachments) && count($attachments) > 0)
        <div class="space-y-6">
            @foreach($attachments as $index => $attachment)
                @php
                    $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                    $isPdf = strtolower($extension) === 'pdf';
                    $url = \Illuminate\Support\Facades\Storage::url($attachment);
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
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
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
                            Abrir / Descargar
                        </a>
                    </div>
                    
                    {{-- Content --}}
                    <div class="p-4">
                        @if($isPdf)
                            <div class="w-full" style="height: 600px;">
                                <iframe src="{{ $url }}" class="w-full h-full border-0 rounded"></iframe>
                            </div>
                        @else
                             <div class="flex items-center justify-center p-8 bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                                <p class="text-gray-500">Vista previa no disponible para este tipo de archivo.</p>
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
            <p class="text-lg font-medium">No hay archivos adjuntos</p>
        </div>
    @endif
</div>
