<div class="space-y-6">
    {{-- Header with Edit Button --}}
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Documentación del Proyecto</h3>
        {{ $this->table }}
    </div>

    {{-- Documentation Sections --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Descripción --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-5">
            <div class="flex items-center gap-2 mb-3">
                <x-heroicon-o-document-text class="w-5 h-5 text-primary-500" />
                <h4 class="font-medium text-gray-900 dark:text-white">Descripción</h4>
            </div>
            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                @if($project->description)
                    {!! $project->description !!}
                @else
                    <p class="text-gray-400 italic">Sin descripción</p>
                @endif
            </div>
        </div>

        {{-- Stack Tecnológico --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-5">
            <div class="flex items-center gap-2 mb-3">
                <x-heroicon-o-code-bracket class="w-5 h-5 text-purple-500" />
                <h4 class="font-medium text-gray-900 dark:text-white">Stack Tecnológico</h4>
            </div>
            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                @if($project->technologies)
                    {!! $project->technologies !!}
                @else
                    <p class="text-gray-400 italic">Sin tecnologías definidas</p>
                @endif
            </div>
        </div>

        {{-- Infraestructura --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-5">
            <div class="flex items-center gap-2 mb-3">
                <x-heroicon-o-server class="w-5 h-5 text-green-500" />
                <h4 class="font-medium text-gray-900 dark:text-white">Infraestructura</h4>
            </div>
            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                @if($project->infrastructure)
                    {!! $project->infrastructure !!}
                @else
                    <p class="text-gray-400 italic">Sin infraestructura definida</p>
                @endif
            </div>
        </div>

        {{-- Notas Técnicas --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-5">
            <div class="flex items-center gap-2 mb-3">
                <x-heroicon-o-wrench-screwdriver class="w-5 h-5 text-amber-500" />
                <h4 class="font-medium text-gray-900 dark:text-white">Notas Técnicas</h4>
            </div>
            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                @if($project->technical_notes)
                    {!! $project->technical_notes !!}
                @else
                    <p class="text-gray-400 italic">Sin notas técnicas</p>
                @endif
            </div>
        </div>
    </div>
</div>
