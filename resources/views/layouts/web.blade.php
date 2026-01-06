<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- Primary Meta Tags --}}
    <title>@yield('title', 'Soluciones TI para Empresas') — {{ config('app.name') }}</title>
    <meta name="title" content="@yield('meta_title', 'GrinTic — Soluciones TI para Empresas')">
    <meta name="description" content="@yield('meta_description', 'Gestión integral de TI, desarrollo de software a medida, infraestructura cloud y ciberseguridad para empresas en Colombia. Simplificamos tu tecnología.')">
    <meta name="keywords" content="@yield('meta_keywords', 'servicios TI Colombia, desarrollo software, infraestructura cloud, soporte técnico empresarial, ciberseguridad, gestión TI, soluciones tecnológicas')">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('meta_title', 'GrinTic — Soluciones TI para Empresas')">
    <meta property="og:description" content="@yield('meta_description', 'Gestión integral de TI, desarrollo de software a medida, infraestructura cloud y ciberseguridad para empresas en Colombia.')">
    <meta property="og:image" content="@yield('og_image', 'https://res.cloudinary.com/dspoaxmvn/image/upload/v1751086807/gr_emkc51.png')">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="es_CO">
    
    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('meta_title', 'GrinTic — Soluciones TI para Empresas')">
    <meta name="twitter:description" content="@yield('meta_description', 'Gestión integral de TI, desarrollo de software a medida, infraestructura cloud y ciberseguridad para empresas en Colombia.')">
    <meta name="twitter:image" content="@yield('og_image', 'https://res.cloudinary.com/dspoaxmvn/image/upload/v1751086807/gr_emkc51.png')">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dspoaxmvn/image/upload/v1751086807/gr_emkc51.png">
    <link rel="apple-touch-icon" href="https://res.cloudinary.com/dspoaxmvn/image/upload/v1751086807/gr_emkc51.png">
    
    {{-- Preconnect for performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://res.cloudinary.com">
    
    {{-- JSON-LD Structured Data for Organization --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ config('app.name') }}",
        "url": "https://grintic.com",
        "logo": "https://res.cloudinary.com/dspoaxmvn/image/upload/v1751086807/gr_emkc51.png",
        "description": "Gestión integral de TI, desarrollo de software a medida, infraestructura cloud y ciberseguridad para empresas en Colombia.",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "CO"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+57-316-550-4399",
            "contactType": "customer service",
            "email": "clientes@grintic.com",
            "availableLanguage": ["Spanish"]
        },
        "sameAs": []
    }
    </script>
    
    {{-- JSON-LD for Local Business --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ProfessionalService",
        "name": "{{ config('app.name') }}",
        "image": "https://res.cloudinary.com/dspoaxmvn/image/upload/v1751086807/gr_emkc51.png",
        "url": "https://grintic.com",
        "telephone": "+57-316-550-4399",
        "email": "clientes@grintic.com",
        "priceRange": "$$",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "CO"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            "opens": "08:00",
            "closes": "18:00"
        },
        "areaServed": {
            "@type": "Country",
            "name": "Colombia"
        },
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Servicios de TI",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Infraestructura & Cloud"
                    }
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Desarrollo de Software a Medida"
                    }
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Ciberseguridad"
                    }
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Soporte Técnico"
                    }
                }
            ]
        }
    }
    </script>
    
    @stack('seo')
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .text-gradient {
            background: linear-gradient(135deg, #1ea1d4 0%, #0a84b4 50%, #045d80 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-slate-600">

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <header class="fixed w-full top-0 z-50 bg-[#0a84b4] shadow-lg">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 group">
                <img src="https://res.cloudinary.com/dspoaxmvn/image/upload/v1751086807/gr_emkc51.png" alt="{{ config('app.name') }}" class="h-8 w-auto brightness-0 invert" />
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-4 py-2 text-sm text-white/80 hover:text-white transition-colors rounded-lg hover:bg-white/10 {{ request()->routeIs('home') ? 'bg-white/10 text-white' : '' }}">Inicio</a>
                <a href="{{ route('servicios') }}" class="px-4 py-2 text-sm text-white/80 hover:text-white transition-colors rounded-lg hover:bg-white/10 {{ request()->routeIs('servicios') ? 'bg-white/10 text-white' : '' }}">Servicios</a>
                <a href="{{ route('nosotros') }}" class="px-4 py-2 text-sm text-white/80 hover:text-white transition-colors rounded-lg hover:bg-white/10 {{ request()->routeIs('nosotros') ? 'bg-white/10 text-white' : '' }}">Nosotros</a>
                <a href="{{ route('contacto') }}" class="px-4 py-2 text-sm text-white/80 hover:text-white transition-colors rounded-lg hover:bg-white/10 {{ request()->routeIs('contacto') ? 'bg-white/10 text-white' : '' }}">Contacto</a>
                
                <div class="w-px h-5 bg-white/20 mx-3"></div>
                
                <a href="/portal/login" class="ml-2 px-5 py-2 text-sm font-medium text-[#0a84b4] bg-white hover:bg-white/90 rounded-lg transition-all hover:shadow-lg">
                    Acceder
                </a>
            </nav>

            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 text-white hover:bg-white/10 rounded-lg transition-colors">
                <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-[#045d80] border-t border-white/10">
            <div class="px-6 py-4 space-y-2">
                <a href="{{ route('home') }}" class="block px-4 py-3 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ request()->routeIs('home') ? 'bg-white/10' : '' }}">Inicio</a>
                <a href="{{ route('servicios') }}" class="block px-4 py-3 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ request()->routeIs('servicios') ? 'bg-white/10' : '' }}">Servicios</a>
                <a href="{{ route('nosotros') }}" class="block px-4 py-3 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ request()->routeIs('nosotros') ? 'bg-white/10' : '' }}">Nosotros</a>
                <a href="{{ route('contacto') }}" class="block px-4 py-3 text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ request()->routeIs('contacto') ? 'bg-white/10' : '' }}">Contacto</a>
                <div class="pt-4 border-t border-white/10">
                    <a href="/portal/login" class="block px-4 py-3 text-center text-[#0a84b4] bg-white hover:bg-white/90 rounded-lg font-semibold transition-colors">Acceder</a>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="pt-16">
        @yield('content')
    </main>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- FOOTER --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <footer class="bg-slate-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-12 mb-12">
                {{-- Brand --}}
                <div class="md:col-span-2">
                    <a href="/" class="flex items-center gap-2.5 mb-4">
                        <img src="https://res.cloudinary.com/dspoaxmvn/image/upload/v1751086807/gr_emkc51.png" alt="{{ config('app.name') }}" class="h-10 w-auto brightness-0 invert" />
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-md">
                        Simplificamos la tecnología de tu empresa. Gestión integral de TI, desarrollo de software y soluciones cloud diseñadas para escalar contigo.
                    </p>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="font-semibold text-white mb-4">Navegación</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="{{ route('servicios') }}" class="hover:text-white transition-colors">Servicios</a></li>
                        <li><a href="{{ route('nosotros') }}" class="hover:text-white transition-colors">Nosotros</a></li>
                        <li><a href="{{ route('contacto') }}" class="hover:text-white transition-colors">Contacto</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="font-semibold text-white mb-4">Contacto</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li>clientes@grintic.com</li>
                        <li>+57 316 550 4399</li>
                    </ul>
                </div>

                {{-- Badge/Image --}}
                <div class="flex items-start justify-end">
                    {{-- Reemplaza el src con la URL de tu imagen --}}
                    <img src="https://res.cloudinary.com/dspoaxmvn/image/upload/v1751087063/protected-data_htwbfd.png" alt="Certificación" class="h-24 w-auto object-contain border-[0px] rounded-lg" />
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-slate-500 hover:text-[#1ea1d4] transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-[#1ea1d4] transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Mobile Menu Script --}}
    <script>
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
