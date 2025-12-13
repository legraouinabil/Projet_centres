<header 
    x-data="{ mobileMenuOpen: false, userDropdownOpen: false, navDropdownOpen: null }" 
    class="bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 sticky top-0 z-40 transition-all duration-300"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side: Logo & Brand -->
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="{{ asset('front/logo.png') }}" alt="Logo" class="h-9 w-auto transition-transform duration-300 group-hover:scale-105">
                </a>
                
              
            </div>

            @auth
            <!-- Center: Desktop Navigation -->
            @php
                $isAdmin = Auth::user()->role === 'admin';
                
                // Define the Menu Structure
                $menuItems = [
                    [
                        'type' => 'link',
                        'label' => 'Tableau de bord',
                        'route' => $isAdmin ? 'admin.dashboard' : 'dashboard',
                        'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'
                    ],
                    [
                        'type' => 'link',
                        'label' => 'Dossiers',
                        'route' => 'dossiers',
                        'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z'
                    ],
                    // NEW DROPDOWN: PERFORMANCES (Impact, RH, RF)
                    [
                        'type' => 'dropdown',
                        'label' => 'Performances',
                        'id' => 'performances', // Unique ID for Alpine
                        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                        'children' => [
                            ['label' => 'Impact', 'route' => 'impacts'], // Verify this route name
                            ['label' => 'RH (Ressources Humaines)', 'route' => 'ressources-humaines'], // Placeholder route
                            ['label' => 'RF (Ressources Financières)', 'route' => 'ressources-financieres'], // Placeholder route
                        ]
                    ],
                    [
                        'type' => 'link',
                        'label' => 'Associations',
                        'route' => 'associations',
                        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
                    ],
                    [
                        'type' => 'link',
                        'label' => 'Centres',
                        'route' => 'centres',
                        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
                    ],
                    // NEW LINK: REPORTS
                    [
                        'type' => 'link',
                        'label' => 'Rapports',
                        'route' => 'reports', // Placeholder route
                        'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                    ],
                ];
            @endphp

            <nav class="hidden lg:flex items-center space-x-1">
                @foreach ($menuItems as $item)
                    @if($item['type'] === 'link')
                        <!-- Standard Link -->
                        @php
                            $isActive = Route::currentRouteName() === $item['route'];
                            $classes = $isActive 
                                ? "bg-emerald-50 text-emerald-700 font-semibold shadow-sm" 
                                : "text-gray-500 hover:text-gray-900 hover:bg-gray-50";
                        @endphp
                        <a href="{{ route($item['route']) }}" 
                           class="px-3 py-2 rounded-lg text-sm transition-all duration-200 flex items-center gap-2 {{ $classes }}">
                            <svg class="w-4 h-4 {{ $isActive ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                            {{ $item['label'] }}
                        </a>
                    @elseif($item['type'] === 'dropdown')
                        <!-- Dropdown Menu -->
                        @php
                            // Check if any child is active to highlight the parent
                            $childRoutes = array_column($item['children'], 'route');
                            $isParentActive = in_array(Route::currentRouteName(), $childRoutes);
                            $parentClasses = $isParentActive
                                ? "bg-emerald-50 text-emerald-700 font-semibold shadow-sm" 
                                : "text-gray-500 hover:text-gray-900 hover:bg-gray-50";
                        @endphp
                        <div class="relative" @click.outside="navDropdownOpen = null">
                            <button 
                                @click="navDropdownOpen = navDropdownOpen === '{{ $item['id'] }}' ? null : '{{ $item['id'] }}'"
                                class="px-3 py-2 rounded-lg text-sm transition-all duration-200 flex items-center gap-2 {{ $parentClasses }}"
                            >
                                <svg class="w-4 h-4 {{ $isParentActive ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                                {{ $item['label'] }}
                                <svg class="w-3 h-3 ml-0.5 transition-transform duration-200" :class="navDropdownOpen === '{{ $item['id'] }}' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <!-- Dropdown Panel -->
                            <div 
                                x-show="navDropdownOpen === '{{ $item['id'] }}'"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 origin-top-left ring-1 ring-black ring-opacity-5"
                                style="display: none;"
                            >
                                @foreach($item['children'] as $child)
                                    @php $isChildActive = Route::currentRouteName() === $child['route']; @endphp
                                    <a href="{{ route($child['route']) }}" class="block px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors {{ $isChildActive ? 'text-emerald-600 font-medium bg-emerald-50/50' : 'text-gray-700' }}">
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </nav>

            <!-- Right Side: User Dropdown & Mobile Toggle -->
            <div class="flex items-center gap-2">
                
                <!-- Notification Bell -->
                <button class="p-2 rounded-full text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 transition hidden sm:block relative">
                    <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full border border-white"></span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>

                <!-- User Dropdown -->
                <div class="relative ml-2">
                    <button @click="userDropdownOpen = !userDropdownOpen" @click.away="userDropdownOpen = false" class="flex items-center gap-2 focus:outline-none p-1 rounded-full hover:bg-gray-50 transition">
                        <div class="h-9 w-9 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center text-emerald-700 font-bold text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="hidden md:flex flex-col items-start pr-2">
                            <span class="text-xs font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-gray-500 uppercase tracking-wide">{{ Auth::user()->role }}</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="userDropdownOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 origin-top-right ring-1 ring-black ring-opacity-5"
                         style="display: none;">
                        
                        <div class="px-4 py-2 border-b border-gray-50 md:hidden">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-emerald-600 transition-colors">Mon Profil</a>
                        @if($isAdmin)
                            <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-emerald-600 transition-colors">Paramètres</a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-50 mt-1">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center lg:hidden ml-2">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500">
                        <span class="sr-only">Menu</span>
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            @endauth
        </div>
    </div>

    <!-- Mobile Menu (Accordion Style) -->
    @auth
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t border-gray-100 bg-white"
         style="display: none;">
        
        <div class="pt-2 pb-3 space-y-1 px-4">
            @foreach ($menuItems as $item)
                @if($item['type'] === 'link')
                    @php $isActive = Route::currentRouteName() === $item['route']; @endphp
                    <a href="{{ route($item['route']) }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium flex items-center gap-3 transition-colors {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-5 h-5 {{ $isActive ? 'text-emerald-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                        {{ $item['label'] }}
                    </a>
                @elseif($item['type'] === 'dropdown')
                    <!-- Mobile Dropdown Accordion -->
                    <div x-data="{ expanded: false }">
                        <button @click="expanded = !expanded" class="w-full flex justify-between items-center px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                                {{ $item['label'] }}
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="expanded" x-collapse class="pl-11 space-y-1 mt-1">
                            @foreach($item['children'] as $child)
                                @php $isChildActive = Route::currentRouteName() === $child['route']; @endphp
                                <a href="{{ route($child['route']) }}" class="block py-2 text-sm rounded-md hover:text-emerald-600 {{ $isChildActive ? 'text-emerald-700 font-bold' : 'text-gray-500' }}">
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endauth
</header>