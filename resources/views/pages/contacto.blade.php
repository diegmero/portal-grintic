@extends('layouts.web')

@section('title', 'Contacto')
@section('meta_title', 'Contacto — Habla con Nuestro Equipo TI | GrinTic')
@section('meta_description', 'Contáctanos por WhatsApp o email. Respuesta rápida para consultas sobre servicios TI, cotizaciones y soporte técnico. +57 316 550 4399.')
@section('meta_keywords', 'contacto GrinTic, cotización servicios TI, soporte técnico Colombia, consulta infraestructura cloud')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-br from-[#0a84b4] to-[#045d80] py-24">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="font-heading text-4xl md:text-5xl font-bold text-white mb-6">
                Contáctanos
            </h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto">
                Estamos listos para ayudarte. Cuéntanos sobre tu proyecto.
            </p>
        </div>
    </section>

    {{-- Contact Content --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                
                {{-- Contact Info --}}
                <div>
                    <span class="text-[#0a84b4] font-semibold text-sm uppercase tracking-wider">Ponte en contacto</span>
                    <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 mt-4 mb-6">
                        Hablemos de tu próximo proyecto
                    </h2>
                    <p class="text-slate-500 leading-relaxed mb-8">
                        Ya sea que necesites una consulta inicial o quieras empezar de inmediato, estamos aquí para ayudarte.
                    </p>

                    <div class="space-y-6">
                        {{-- WhatsApp --}}
                        <a href="https://wa.me/573165504399" target="_blank" class="flex items-center gap-4 p-4 bg-green-50 border border-green-200 rounded-2xl hover:bg-green-100 transition-colors group">
                            <div class="p-3 bg-green-500 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">WhatsApp</p>
                                <p class="text-slate-500 text-sm">+57 316 550 4399</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>

                        {{-- Email --}}
                        <a href="mailto:clientes@grintic.com" class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl hover:bg-slate-100 transition-colors group">
                            <div class="p-3 bg-[#0a84b4] rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Correo Electrónico</p>
                                <p class="text-slate-500 text-sm">clientes@grintic.com</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-400 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Contact Form Placeholder --}}
                <div class="bg-slate-50 rounded-3xl p-8 md:p-12">
                    <h3 class="font-heading text-2xl font-bold text-slate-900 mb-6">Envíanos un mensaje</h3>
                    <form class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nombre</label>
                            <input type="text" placeholder="Tu nombre completo" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0a84b4] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Correo Electrónico</label>
                            <input type="email" placeholder="tu@email.com" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0a84b4] focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Mensaje</label>
                            <textarea rows="4" placeholder="Cuéntanos sobre tu proyecto..." class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0a84b4] focus:border-transparent transition-all resize-none"></textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-4 bg-[#0a84b4] text-white font-semibold rounded-xl hover:bg-[#045d80] transition-all shadow-lg shadow-[#0a84b4]/20">
                            Enviar Mensaje
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection
