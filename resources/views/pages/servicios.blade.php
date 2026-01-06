@extends('layouts.web')

@section('title', 'Servicios de TI para Empresas')
@section('meta_title', 'Servicios TI — Infraestructura, Desarrollo y Ciberseguridad | GrinTic')
@section('meta_description', 'Servicios de infraestructura cloud, desarrollo de software a medida, ciberseguridad, soporte técnico y consultoría TI para empresas en Colombia. Precios competitivos.')
@section('meta_keywords', 'servicios TI empresas, infraestructura cloud Colombia, desarrollo software personalizado, ciberseguridad empresarial, soporte técnico')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-[#0a84b4] to-[#045d80] py-24">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="font-heading text-4xl md:text-5xl font-bold text-white mb-6">
                Nuestros Servicios
            </h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto">
                Soluciones tecnológicas integrales diseñadas para impulsar el crecimiento de tu empresa.
            </p>
        </div>
    </section>

    {{-- Services Grid --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Service 1 --}}
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 hover:border-[#1ea1d4] transition-all duration-500 hover:shadow-xl">
                    <div class="p-4 rounded-2xl bg-[#1ea1d4]/10 border border-[#1ea1d4]/20 w-fit mb-6">
                        <svg class="w-10 h-10 text-[#0a84b4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Infraestructura & Cloud</h3>
                    <p class="text-slate-500 leading-relaxed mb-6">
                        Diseñamos, implementamos y gestionamos infraestructuras cloud escalables. Servidores dedicados, VPS, y arquitecturas en AWS, GCP o Azure con uptime garantizado.
                    </p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[#0a84b4]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Servidores Dedicados</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[#0a84b4]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> VPS Administrados</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[#0a84b4]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Migración Cloud</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[#0a84b4]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Monitoreo 24/7</li>
                    </ul>
                </div>

                {{-- Service 2 --}}
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 hover:border-[#045d80] transition-all duration-500 hover:shadow-xl">
                    <div class="p-4 rounded-2xl bg-[#045d80]/10 border border-[#045d80]/20 w-fit mb-6">
                        <svg class="w-10 h-10 text-[#045d80]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Desarrollo a Medida</h3>
                    <p class="text-slate-500 leading-relaxed mb-6">
                        Creamos software personalizado que se adapta a tus procesos. Desde sitios corporativos hasta sistemas SaaS complejos con las mejores prácticas.
                    </p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[#045d80]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Aplicaciones Web</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[#045d80]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> APIs REST/GraphQL</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[#045d80]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Integraciones</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[#045d80]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Automatizaciones</li>
                    </ul>
                </div>

                {{-- Service 3 --}}
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 hover:border-emerald-400 transition-all duration-500 hover:shadow-xl">
                    <div class="p-4 rounded-2xl bg-emerald-100 border border-emerald-200 w-fit mb-6">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Ciberseguridad</h3>
                    <p class="text-slate-500 leading-relaxed mb-6">
                        Protegemos tus activos digitales con estrategias proactivas. Auditorías, pentest, backups encriptados y monitoreo continuo.
                    </p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Auditorías de Seguridad</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Pentesting</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Backups Encriptados</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Respuesta a Incidentes</li>
                    </ul>
                </div>

                {{-- Service 4 --}}
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 hover:border-amber-400 transition-all duration-500 hover:shadow-xl">
                    <div class="p-4 rounded-2xl bg-amber-100 border border-amber-200 w-fit mb-6">
                        <svg class="w-10 h-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Soporte Técnico</h3>
                    <p class="text-slate-500 leading-relaxed mb-6">
                        Mesa de ayuda profesional con SLAs definidos. Mantenimiento preventivo y resolución de incidencias con tiempos de respuesta garantizados.
                    </p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Mesa de Ayuda</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Mantenimiento Preventivo</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> SLAs Definidos</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Soporte Remoto/Presencial</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 bg-[#0a84b4]">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-white mb-6">
                ¿Listo para transformar tu infraestructura?
            </h2>
            <p class="text-white/80 mb-8">
                Contáctanos hoy y descubre cómo podemos ayudarte.
            </p>
            <a href="{{ route('contacto') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-[#0a84b4] font-semibold rounded-xl hover:bg-white/90 transition-all shadow-lg">
                Solicitar Cotización
            </a>
        </div>
    </section>
@endsection
