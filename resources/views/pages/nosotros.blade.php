@extends('layouts.web')

@section('title', 'Nosotros')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-[#0a84b4] to-[#045d80] py-24">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="font-heading text-4xl md:text-5xl font-bold text-white mb-6">
                Quiénes Somos
            </h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto">
                Un equipo apasionado por la tecnología, comprometido con el éxito de tu negocio.
            </p>
        </div>
    </section>

    {{-- About Content --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                
                {{-- Text --}}
                <div>
                    <span class="text-[#0a84b4] font-semibold text-sm uppercase tracking-wider">Nuestra Historia</span>
                    <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 mt-4 mb-6">
                        Impulsando empresas con tecnología desde Colombia
                    </h2>
                    <p class="text-slate-500 leading-relaxed mb-6">
                        Nacimos con la visión de simplificar la gestión tecnológica para empresas de todos los tamaños. Creemos que la tecnología debe ser un habilitador, no un obstáculo.
                    </p>
                    <p class="text-slate-500 leading-relaxed mb-6">
                        Nuestro enfoque combina expertise técnico con un profundo entendimiento de las necesidades empresariales. No solo implementamos soluciones, construimos relaciones de largo plazo.
                    </p>
                    <div class="grid grid-cols-2 gap-6 mt-8">
                        <div class="text-center p-6 bg-slate-50 rounded-2xl">
                            <span class="text-4xl font-bold text-[#0a84b4]">50+</span>
                            <p class="text-slate-500 text-sm mt-2">Clientes Activos</p>
                        </div>
                        <div class="text-center p-6 bg-slate-50 rounded-2xl">
                            <span class="text-4xl font-bold text-[#0a84b4]">99.9%</span>
                            <p class="text-slate-500 text-sm mt-2">Uptime Garantizado</p>
                        </div>
                    </div>
                </div>

                {{-- Image placeholder --}}
                <div class="bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl h-[400px] flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-24 h-24 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-slate-400">Equipo {{ config('app.name') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Values --}}
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#0a84b4] font-semibold text-sm uppercase tracking-wider">Lo que nos define</span>
                <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 mt-4">
                    Nuestros Valores
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8">
                    <div class="w-16 h-16 bg-[#1ea1d4]/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#0a84b4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Excelencia</h3>
                    <p class="text-slate-500 text-sm">Nos esforzamos por superar las expectativas en cada proyecto.</p>
                </div>

                <div class="text-center p-8">
                    <div class="w-16 h-16 bg-[#1ea1d4]/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#0a84b4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Compromiso</h3>
                    <p class="text-slate-500 text-sm">Tu éxito es nuestro éxito. Trabajamos como partners, no proveedores.</p>
                </div>

                <div class="text-center p-8">
                    <div class="w-16 h-16 bg-[#1ea1d4]/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-[#0a84b4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Innovación</h3>
                    <p class="text-slate-500 text-sm">Constantemente exploramos nuevas tecnologías para ofrecerte lo mejor.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
