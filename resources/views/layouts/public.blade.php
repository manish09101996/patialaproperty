<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Patiala Property & Home Services')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,900&display=swap" rel="stylesheet" />

    <!-- Scripts and Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Custom scrollbar for premium feel */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 flex flex-col min-h-screen">

    <!-- Scroll-transformed Header -->
    @if(request()->routeIs('home'))
        <header id="main-header" class="fixed w-full top-0 left-0 z-50 transition-all duration-300 bg-transparent text-white border-b border-white/10 py-4">
    @else
        <header id="main-header" class="sticky top-0 z-50 bg-white text-slate-900 border-b border-slate-100 shadow-sm py-3.5">
    @endif
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-10 items-center">
                
                <!-- Logo with Patiala Badge -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <span id="logo-text" class="text-2xl font-black tracking-tight {{ request()->routeIs('home') ? 'text-white' : 'bg-gradient-to-r from-blue-700 to-indigo-700 bg-clip-text text-transparent' }} transition-colors duration-300">Patiala Property</span>
                        <span class="bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded font-black uppercase tracking-wider shadow-sm">Patiala Only</span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden md:flex space-x-7 font-semibold text-sm items-center">
                    <!-- Rent Property (First & Highlighted) -->
                    <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="font-bold transition-all duration-200 flex items-center space-x-1.5 bg-blue-600 text-white hover:bg-blue-700 px-3.5 py-1.5 rounded-full shadow-md shadow-blue-500/20 text-xs">
                        <span class="h-2 w-2 rounded-full bg-amber-400 animate-pulse"></span>
                        <span>Rent Property</span>
                    </a>

                    <a href="{{ route('properties.index', ['purpose' => 'sell']) }}" class="hover:text-blue-600 transition-colors duration-200">Buy Property</a>
                    <a href="{{ route('properties.create') }}" class="hover:text-blue-600 transition-colors duration-200">Sell Property</a>
                    
                    <div class="relative group">
                        <a href="#services-section" class="hover:text-blue-600 flex items-center space-x-1 focus:outline-none py-2">
                            <span>Services</span>
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                        <!-- Dropdown with NOC, CLU, CRO, PUDA, Architecture, 3D -->
                        <div class="absolute left-0 mt-1 w-56 bg-white text-slate-800 rounded-2xl shadow-xl border border-slate-100 py-2.5 hidden group-hover:block transition-all z-50">
                            <span class="block px-4 py-1 text-[10px] uppercase font-black tracking-wider text-blue-600">Professional Services</span>
                            <a href="#services-section" class="block px-4 py-1.5 text-xs font-semibold hover:bg-slate-50 hover:text-blue-600">NOC Services</a>
                            <a href="#services-section" class="block px-4 py-1.5 text-xs font-semibold hover:bg-slate-50 hover:text-blue-600">CLU Services</a>
                            <a href="#services-section" class="block px-4 py-1.5 text-xs font-semibold hover:bg-slate-50 hover:text-blue-600">CRO Services</a>
                            <a href="#services-section" class="block px-4 py-1.5 text-xs font-semibold hover:bg-slate-50 hover:text-blue-600">PUDA Approval</a>
                            <a href="#services-section" class="block px-4 py-1.5 text-xs font-semibold hover:bg-slate-50 hover:text-blue-600">Architectural Planning</a>
                            <a href="#services-section" class="block px-4 py-1.5 text-xs font-semibold hover:bg-slate-50 hover:text-blue-600">3D Design & Visualization</a>
                        </div>
                    </div>

                    <a href="#insights-section" class="hover:text-blue-600 transition-colors duration-200">Property Insights</a>
                </nav>

                <!-- Actions / Auth Dropdown -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Wishlist link -->
                        <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors relative" title="Wishlist">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </a>

                        <!-- Notification Placeholder -->
                        <button class="hover:text-blue-600 transition-colors" title="Notifications">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </button>

                        <!-- User Menu Dropdown -->
                        <div class="relative" id="user-menu-root">
                            <button onclick="toggleUserDropdown()" class="flex items-center space-x-2 focus:outline-none py-1.5 px-2.5 rounded-lg border border-slate-200/50 bg-slate-500/10 hover:bg-slate-500/20 transition-all">
                                <div class="h-6 w-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:inline text-xs font-bold">{{ Auth::user()->name }}</span>
                                <svg class="h-3 w-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="user-dropdown-menu" class="absolute right-0 mt-2 w-52 bg-white text-slate-800 rounded-2xl shadow-2xl border border-slate-100 py-2.5 hidden z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <span class="font-bold block text-sm">{{ Auth::user()->name }}</span>
                                    <span class="text-[10px] text-slate-400 capitalize">{{ Auth::user()->role }} Account</span>
                                </div>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-xs font-bold hover:bg-slate-50 hover:text-blue-600 transition-colors">Dashboard Overview</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs font-bold hover:bg-slate-50 hover:text-blue-600 transition-colors">Account Settings</a>
                                <div class="border-t border-slate-100 my-1.5"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold hover:text-blue-600 transition-colors">Sign In</a>
                        <a href="{{ route('otp.login.form') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35 transition-all">List Property</a>
                    @endauth

                    <!-- Mobile Hamburger -->
                    <button onclick="toggleMobileMenu()" class="md:hidden focus:outline-none p-1.5 rounded-lg border border-slate-200/40 hover:bg-slate-500/10 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Slide-down Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white shadow-xl py-4 space-y-3 px-6 text-slate-800 font-semibold text-sm">
            <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="flex items-center justify-between py-2 px-3 bg-blue-50 text-blue-700 rounded-xl font-bold">
                <span class="flex items-center space-x-2">
                    <span class="h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                    <span>Rent Property</span>
                </span>
                <span class="bg-amber-400 text-slate-950 text-[10px] px-2 py-0.5 rounded-full uppercase font-black">Popular</span>
            </a>
            <a href="{{ route('properties.index', ['purpose' => 'sell']) }}" class="block py-1 hover:text-blue-600">Buy Property</a>
            <a href="{{ route('properties.create') }}" class="block py-1 hover:text-blue-600">Sell Property</a>
            <a href="#services-section" class="block py-1 hover:text-blue-600">Property & Professional Services</a>
            <a href="#insights-section" class="block py-1 hover:text-blue-600">Property Insights</a>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-grow">
        @if(session('status'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3.5 rounded-xl relative role=alert shadow-sm text-sm">
                    <span class="block sm:inline font-semibold">{{ session('status') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3.5 rounded-xl relative role=alert shadow-sm text-sm">
                    <span class="block sm:inline font-semibold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer: Deep Navy Theme -->
    <footer class="bg-slate-950 text-slate-350 py-16 border-t border-slate-900 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                
                <!-- Brand Descriptor -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl font-black text-white">Patiala Property</span>
                        <span class="bg-amber-500 text-white text-[9px] px-1.5 py-0.5 rounded font-black">Patiala ONLY</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">Connecting buyers, sellers, tenants, and professional service experts across Patiala. Real estate simplified.</p>
                </div>
                
                <!-- Explore Links -->
                <div>
                    <h4 class="text-white text-xs font-black uppercase tracking-wider mb-6">Explore Portal</h4>
                    <ul class="space-y-3.5 text-sm text-slate-400">
                        <li><a href="{{ route('properties.index', ['purpose' => 'sell']) }}" class="hover:text-white transition-colors">Buy Residential Property</a></li>
                        <li><a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="hover:text-white transition-colors">Rent Flats & Houses</a></li>
                        <li><a href="{{ route('properties.create') }}" class="hover:text-white transition-colors">Post Property Listing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors opacity-50">Local Home Services (Phase 2)</a></li>
                    </ul>
                </div>
                
                <!-- Areas Links -->
                <div>
                    <h4 class="text-white text-xs font-black uppercase tracking-wider mb-6">Top Patiala Localities</h4>
                    <ul class="space-y-3.5 text-sm text-slate-400">
                        <li><a href="{{ route('properties.index', ['search' => 'Model Town']) }}" class="hover:text-white transition-colors">Model Town</a></li>
                        <li><a href="{{ route('properties.index', ['search' => 'Urban Estate']) }}" class="hover:text-white transition-colors">Urban Estate</a></li>
                        <li><a href="{{ route('properties.index', ['search' => 'Leela Bhawan']) }}" class="hover:text-white transition-colors">Leela Bhawan</a></li>
                        <li><a href="{{ route('properties.index', ['search' => 'Tripuri']) }}" class="hover:text-white transition-colors">Tripuri</a></li>
                    </ul>
                </div>
                
                <!-- Support Links -->
                <div>
                    <h4 class="text-white text-xs font-black uppercase tracking-wider mb-6">Contact & Support</h4>
                    <ul class="space-y-3.5 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQs</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Use</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="mt-16 pt-8 border-t border-slate-900 text-center text-xs text-slate-500 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p>&copy; {{ date('Y') }} Patiala Property. All Rights Reserved. Exclusively for Patiala, Punjab.</p>
                <div class="flex items-center space-x-1.5 font-semibold text-slate-400">
                    <span>Made for Patiala</span>
                    <span class="text-red-500 text-sm">❤️</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Header Transmute Script (Only on Home Route) -->
    <script>
        const header = document.getElementById('main-header');
        const logoText = document.getElementById('logo-text');
        
        // Transparent to White Scroll logic
        @if(request()->routeIs('home'))
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.remove('bg-transparent', 'text-white', 'border-white/10', 'py-4');
                header.classList.add('bg-white', 'text-slate-900', 'border-slate-100', 'shadow-sm', 'py-3.5');
                logoText.classList.remove('text-white');
                logoText.classList.add('bg-gradient-to-r', 'from-blue-700', 'to-indigo-700', 'bg-clip-text', 'text-transparent');
            } else {
                header.classList.remove('bg-white', 'text-slate-900', 'border-slate-100', 'shadow-sm', 'py-3.5');
                header.classList.add('bg-transparent', 'text-white', 'border-white/10', 'py-4');
                logoText.classList.remove('bg-gradient-to-r', 'from-blue-700', 'to-indigo-700', 'bg-clip-text', 'text-transparent');
                logoText.classList.add('text-white');
            }
        });
        @endif

        // User dropdown toggle
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown-menu');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', (e) => {
            const dropdown = document.getElementById('user-dropdown-menu');
            const root = document.getElementById('user-menu-root');
            if (dropdown && root && !root.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Mobile menu hamburger toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>

</body>
</html>
