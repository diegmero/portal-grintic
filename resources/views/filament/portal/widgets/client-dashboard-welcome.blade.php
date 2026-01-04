<x-filament-widgets::widget>
    <div class="flex flex-col items-center justify-center p-6 space-y-10 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
        {{-- Welcome Header --}}
        <div class="text-center max-w-2xl">
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-primary-400 dark:from-primary-400 dark:to-primary-200">
                ¡Bienvenido {{ auth()->user()->client?->company_name ?? 'Estimado Cliente' }}!
            </h1>
            
            {{-- Spacer --}}
            <div class="h-10"></div>

            <p class="text-gray-600 dark:text-gray-400 text-lg">
                Me alegra tenerte aquí. Desde este panel podrás gestionar tus proyectos, suscripciones y revisar el estado de tus horas de soporte en tiempo real.
            </p>

            {{-- Spacer --}}
            <div class="h-10"></div>
        </div>

        {{-- Info Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl">
            
            {{-- Card 1: Services --}}
            <div class="flex flex-col p-6 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 h-full transition-transform hover:scale-[1.01] hover:shadow-md">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <x-heroicon-o-briefcase class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        Nuestros Servicios
                    </h2>
                </div>
                <div class="flex-grow space-y-3 text-gray-600 dark:text-gray-400">
                    <p>Potenciamos tu negocio con soluciones tecnológicas a medida:</p>
                    <ul class="space-y-2 list-disc list-inside ml-1">
                        <li>Gestión Corporativa TI</li>
                        <li>Aplicaciones y paginas web</li>
                        <li>Infraestructura Cloud</li>
                        <li>Mantenimiento y Soporte Continuo</li>
                        <li>Consultoría en TI</li>
                        <li>Desarrollo de Software</li>
                        <li>Inteligencia Artificial</li>
                        <li>Automatización</li>
                        <li>Seguridad de la información</li>
                    </ul>
                </div>
            </div>

            {{-- Card 2: Contact Info --}}
            <div class="flex flex-col p-6 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 h-full transition-transform hover:scale-[1.01] hover:shadow-md">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        ¿Necesitas Ayuda?
                    </h2>
                </div>
                <div class="flex-grow space-y-3 text-gray-600 dark:text-gray-400">
                    <p>Estamos disponibles para resolver tus dudas y atender incidencias.</p>
                    <div class="space-y-2 mt-4">
                        <div class="flex items-center space-x-2 gap-2">
                            <x-heroicon-m-envelope class="w-5 h-5 text-gray-400" />
                            <span>clientes@grintic.com</span>
                        </div>
                        <div class="flex items-center space-x-2 gap-2">
                            <x-heroicon-m-phone class="w-5 h-5 text-gray-400" />
                            <span>+57 3165504399</span>
                        </div>
                        <div class="flex items-center space-x-2 gap-2">
                            <x-heroicon-m-clock class="w-5 h-5 text-gray-400" />
                            <span>Lun - Vie, 8am - 6pm</span>
                        </div>
                        <div class="flex items-center space-x-2 gap-2">
                            <span class="text-lg">🇨🇴</span>
                            <span>Colombia</span>
                        </div>
                        <div class="flex items-center space-x-2 gap-2">
                            <span class="text-lg">🇺🇸</span>
                            <span>EE.UU</span>
                        </div>
                        <div class="flex items-center space-x-2 gap-2">
                            <span class="text-lg">🇨🇷</span>
                            <span>Costa Rica</span>
                        </div>
                        <div class="flex items-center space-x-2 gap-2">
                            <span class="text-lg">🇲🇽</span>
                            <span>México</span>
                        </div>
                        <div class="flex items-center space-x-2 gap-2">
                            <span class="text-lg">🇪🇸</span>
                            <span>España</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact CTA --}}
        <div class="pt-6">
            <a
                href="https://wa.me/573165504399"
                target="_blank"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-white transition-all bg-primary-600 rounded-lg shadow-md hover:bg-primary-500 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                <span>Contactar</span>
            </a>
        </div>
    </div>
</x-filament-widgets::widget>
