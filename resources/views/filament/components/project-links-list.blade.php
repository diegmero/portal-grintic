<div class="space-y-3">
    @forelse($links as $link)
        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
            <div class="flex-shrink-0">
                @switch($link->type->value ?? 'other')
                    @case('repository')
                        <x-heroicon-o-code-bracket class="w-5 h-5 text-purple-500" />
                        @break
                    @case('documentation')
                        <x-heroicon-o-book-open class="w-5 h-5 text-blue-500" />
                        @break
                    @case('design')
                        <x-heroicon-o-paint-brush class="w-5 h-5 text-pink-500" />
                        @break
                    @case('staging')
                    @case('production')
                        <x-heroicon-o-globe-alt class="w-5 h-5 text-green-500" />
                        @break
                    @default
                        <x-heroicon-o-link class="w-5 h-5 text-gray-500" />
                @endswitch
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-900 dark:text-white truncate">{{ $link->title }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $link->url }}</p>
            </div>
            <div class="flex-shrink-0 flex gap-2">
                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full 
                    @switch($link->type->value ?? 'other')
                        @case('repository') bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300 @break
                        @case('documentation') bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 @break
                        @case('design') bg-pink-100 text-pink-700 dark:bg-pink-900 dark:text-pink-300 @break
                        @case('staging') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300 @break
                        @case('production') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 @break
                        @default bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                    @endswitch
                ">
                    {{ $link->type?->getLabel() ?? 'Otro' }}
                </span>
                <a href="{{ $link->url }}" target="_blank" 
                   class="inline-flex items-center justify-center p-1.5 rounded-lg text-gray-500 hover:text-info-500 hover:bg-info-50 dark:hover:bg-info-900/50 transition-colors">
                    <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                </a>
            </div>
        </div>
    @empty
        <div class="text-center py-6 text-gray-500">
            <x-heroicon-o-link class="w-8 h-8 mx-auto mb-2 opacity-50" />
            <p>No hay enlaces guardados</p>
        </div>
    @endforelse
</div>
