@props(['files'])

<div x-data="{ activeIndex: 0 }" class="flex flex-col h-full" style="min-height: 500px;">
    @if(count($files) > 1)
        <!-- File Selector (Tabs) -->
        <div class="flex space-x-2 overflow-x-auto pb-4 border-b border-gray-200 dark:border-gray-700 mb-4">
            @foreach($files as $index => $file)
                <button 
                    type="button"
                    @click="activeIndex = {{ $index }}"
                    :class="{ 
                        'bg-primary-50 text-primary-600 border-primary-500 ring-1 ring-primary-500': activeIndex === {{ $index }},
                        'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400': activeIndex !== {{ $index }}
                    }"
                    class="flex items-center space-x-2 px-3 py-2 text-sm font-medium rounded-lg border transition-all whitespace-nowrap"
                >
                    <span>{{ $file['type'] === 'pdf' ? '📄' : '🖼️' }}</span>
                    <span>{{ Str::limit($file['name'], 20) }}</span>
                </button>
            @endforeach
        </div>
    @endif

    <!-- Content Area -->
    <div class="flex-1 relative bg-gray-50 dark:bg-gray-900 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        @foreach($files as $index => $file)
            <div x-show="activeIndex === {{ $index }}" class="w-full h-full" style="display: none;">
                @if($file['type'] === 'pdf')
                    <iframe src="{{ $file['url'] }}" class="w-full h-full border-0"></iframe>
                @else
                    <div class="w-full h-full flex items-center justify-center p-4">
                        <img src="{{ $file['url'] }}" class="max-w-full max-h-full object-contain shadow-sm rounded" alt="{{ $file['name'] }}">
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
