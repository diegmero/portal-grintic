@extends('layouts.web')

@section('title', 'Soluciones TI para Empresas en Colombia')

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        position: relative;
        overflow: hidden;
    }
    .hero-gradient::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 50%, rgba(99, 102, 241, 0.08) 0%, transparent 50%),
                    radial-gradient(circle at 70% 80%, rgba(168, 85, 247, 0.06) 0%, transparent 40%),
                    radial-gradient(circle at 90% 20%, rgba(59, 130, 246, 0.06) 0%, transparent 30%);
        animation: pulse 8s ease-in-out infinite alternate;
    }
    @keyframes pulse {
        0% { transform: translate(0, 0) scale(1); opacity: 0.8; }
        100% { transform: translate(2%, -2%) scale(1.05); opacity: 1; }
    }
    .grid-lines {
        background-image: 
            linear-gradient(rgba(0,0,0,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,0.05) 1px, transparent 1px);
        background-size: 60px 60px;
    }
</style>
@endpush

@section('content')
    {{-- Hero --}}
    <section class="hero-gradient grid-lines min-h-screen flex items-center justify-center relative">
        <div class="max-w-5xl mx-auto px-6 pt-24 pb-32 text-center relative z-10">
            
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-[#1ea1d4]/30 bg-[#1ea1d4]/10 mb-8">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#1ea1d4] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#0a84b4]"></span>
                </span>
                <span class="text-xs font-semibold text-[#045d80] uppercase tracking-wider">Pronto Disponible</span>
            </div>

            {{-- Headline --}}
            <h1 class="font-heading text-5xl sm:text-6xl lg:text-7xl font-extrabold text-slate-900 leading-[1.1] mb-6">
                Simplificamos la <br>
                <span class="text-gradient">tecnología de tu empresa.</span>
            </h1>

            {{-- Subheadline --}}
            <p class="text-lg sm:text-xl text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                Gestión integral de TI, desarrollo de software y soluciones cloud diseñadas para escalar contigo. Todo en un solo lugar.
            </p>

            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="https://wa.me/573165504399" target="_blank" class="group w-full sm:w-auto px-8 py-4 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10 flex items-center justify-center gap-3">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    <span>Contactar Ahora</span>
                </a>
                <a href="{{ route('servicios') }}" class="w-full sm:w-auto px-8 py-4 text-white bg-[#0a84b4] font-semibold rounded-xl border border-[#0a84b4] hover:bg-[#0a84b4]/10 hover:text-[#0a84b4] transition-all flex items-center justify-center gap-2 group">
                    <span>Ver Servicios</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            {{-- Social Proof --}}
            <div class="mt-16 pt-10 flex flex-col sm:flex-row items-center justify-center gap-8 text-sm text-slate-400">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span class="text-slate-500">+50 Proyectos Completados</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="text-slate-500">Priorizamos Seguridad</span>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white to-transparent"></div>
    </section>

    {{-- Services Bento Grid --}}
    <section class="py-10 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[#0a84b4] font-semibold text-sm uppercase tracking-wider">Nuestros Servicios</span>
                <h2 class="font-heading text-4xl md:text-5xl font-bold text-slate-900 mt-4">
                    Todo lo que tu negocio necesita
                </h2>
            </div>

            {{-- Bento Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                
                {{-- Card 1: Big --}}
                <div class="lg:col-span-2 group p-8 rounded-3xl bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 hover:border-[#1ea1d4] transition-all duration-500 hover:shadow-xl hover:shadow-[#0a84b4]/10">
                    <div class="flex items-start gap-6">
                        <div class="p-4 rounded-2xl bg-[#1ea1d4]/10 border border-[#1ea1d4]/20">
                            <svg class="w-8 h-8 text-[#0a84b4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800 mb-3">Infraestructura & Cloud</h3>
                            <p class="text-slate-500 leading-relaxed">
                                Servidores dedicados, VPS, y arquitecturas escalables en AWS, GCP o Azure. Migramos, optimizamos y mantenemos tu infraestructura con uptime garantizado.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="group p-8 rounded-3xl bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 hover:border-[#045d80] transition-all duration-500 hover:shadow-xl hover:shadow-[#045d80]/10">
                    <div class="p-4 rounded-2xl bg-[#045d80]/10 border border-[#045d80]/20 w-fit mb-6">
                        <svg class="w-8 h-8 text-[#045d80]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Desarrollo a Medida</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Aplicaciones web, APIs, sistemas internos y automatizaciones construidas con las mejores prácticas.
                    </p>
                </div>

                {{-- Card 3 --}}
                <div class="group p-8 rounded-3xl bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 hover:border-emerald-300 transition-all duration-500 hover:shadow-xl hover:shadow-emerald-500/5">
                    <div class="p-4 rounded-2xl bg-emerald-100 border border-emerald-200 w-fit mb-6">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Ciberseguridad</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Auditorías de seguridad, pentesting, backups encriptados y monitoreo proactivo 24/7.
                    </p>
                </div>

                {{-- Card 4 --}}
                <div class="group p-8 rounded-3xl bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 hover:border-amber-300 transition-all duration-500 hover:shadow-xl hover:shadow-amber-500/5">
                    <div class="p-4 rounded-2xl bg-amber-100 border border-amber-200 w-fit mb-6">
                        <svg class="w-8 h-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Soporte Técnico</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Mesa de ayuda, mantenimiento preventivo y resolución de incidencias con SLAs definidos.
                    </p>
                </div>

                {{-- Card 5 --}}
                <div class="group p-8 rounded-3xl bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 hover:border-rose-300 transition-all duration-500 hover:shadow-xl hover:shadow-rose-500/5">
                    <div class="p-4 rounded-2xl bg-rose-100 border border-rose-200 w-fit mb-6">
                        <svg class="w-8 h-8 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Consultoría TI</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Diagnóstico tecnológico, roadmaps de transformación digital y asesoría estratégica.
                    </p>
                </div>

            </div>
        </div>
    </section>
@endsection
