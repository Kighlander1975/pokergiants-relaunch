<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Backend</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/css/backend/be_layout.css', 'resources/js/app.js'])
</head>

<body class="antialiased backend-body">
    <div class="min-h-screen bg-gray-100 flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-gray-800 text-white w-64 min-h-screen transition-all duration-300 ease-in-out">
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold sidebar-text">Verwaltung</h2>
                    <button id="sidebar-toggle" class="text-gray-400 hover:text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <nav class="mt-8">
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    <span class="sidebar-text">Übersicht</span>
                </a>

                <!-- Verwaltung Dropdown -->
                <div class="sidebar-dropdown">
                    <button class="sidebar-dropdown-toggle flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 w-full text-left">
                        <svg class="w-5 h-5 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="sidebar-text">Verwaltung</span>
                        <svg class="w-4 h-4 ml-auto sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="sidebar-dropdown-content hidden pl-8">
                        <a href="{{ route('admin.users') }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.users*') ? 'bg-gray-700 text-white' : '' }}">
                            <span class="sidebar-text">Benutzer</span>
                        </a>
                        <a href="{{ route('admin.tournaments.index') }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.tournaments*') ? 'bg-gray-700 text-white' : '' }}">
                            <span class="sidebar-text">Turniere</span>
                        </a>
                        <a href="{{ route('admin.locations.index') }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.locations*') ? 'bg-gray-700 text-white' : '' }}">
                            <span class="sidebar-text">Locations</span>
                        </a>
                        <a href="{{ route('admin.news.index') }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.news*') ? 'bg-gray-700 text-white' : '' }}">
                            <span class="sidebar-text">News</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.views.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.views.index') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span class="sidebar-text">Views</span>
                </a>

                <div class="mt-8">
                    <h3 class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider sidebar-text">Views</h3>
                    <a href="{{ route('admin.sections.create') }}" class="sidebar-link flex items-center px-4 py-3 pl-8 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                        <svg class="w-4 h-4 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="sidebar-text">Neue Sektion</span>
                    </a>

                    <!-- Headlines Dropdown -->
                    <div class="sidebar-dropdown">
                        <button class="sidebar-dropdown-toggle flex items-center px-4 py-3 pl-8 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 w-full text-left">
                            <svg class="w-4 h-4 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="sidebar-text">Headlines</span>
                            <svg class="w-4 h-4 ml-auto sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="sidebar-dropdown-content hidden pl-8">
                            @foreach(\App\Models\Section::all() as $section)
                            @if($section->headline)
                            <a href="{{ route('admin.headlines.edit', $section->headline) }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.headlines.edit') && request()->route('headline')->id == $section->headline->id ? 'bg-gray-700 text-white' : '' }}">
                                <span class="sidebar-text">{{ ucfirst($section->section_name) }}</span>
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Widgets Dropdown -->
                    <div class="sidebar-dropdown">
                        <button class="sidebar-dropdown-toggle flex items-center px-4 py-3 pl-8 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 w-full text-left">
                            <svg class="w-4 h-4 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="sidebar-text">Widgets</span>
                            <svg class="w-4 h-4 ml-auto sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="sidebar-dropdown-content hidden pl-8">
                            @foreach(\App\Models\Section::all() as $section)
                            <a href="{{ route('admin.views.' . $section->section_name) }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.views.' . $section->section_name) ? 'bg-gray-700 text-white' : '' }}">
                                <span class="sidebar-text">{{ ucfirst($section->section_name) }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Add more navigation items here -->
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navbar -->
            <nav class="bg-white border-b border-gray-100 shadow-sm">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <button id="sidebar-mobile-toggle" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <h1 class="text-xl font-semibold text-gray-900">{{ $title ?? 'Backend' }}</h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 text-sm">Zur Website</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-gray-900 text-sm">Abmelden</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @yield('content-title')
                @yield('content-body')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarMobileToggle = document.getElementById('sidebar-mobile-toggle');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const sidebarIcons = document.querySelectorAll('.sidebar-icon');

            let isCollapsed = false;

            function toggleSidebar() {
                isCollapsed = !isCollapsed;

                if (isCollapsed) {
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-16');
                    sidebarTexts.forEach(text => text.classList.add('hidden'));
                    sidebarIcons.forEach(icon => icon.classList.remove('mr-3'));
                } else {
                    sidebar.classList.remove('w-16');
                    sidebar.classList.add('w-64');
                    sidebarTexts.forEach(text => text.classList.remove('hidden'));
                    sidebarIcons.forEach(icon => icon.classList.add('mr-3'));
                }
            }

            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarMobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('hidden');
                sidebar.classList.toggle('md:block');
            });

            // Load saved state from localStorage
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                toggleSidebar();
            }

            // Save state to localStorage
            sidebarToggle.addEventListener('click', function() {
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });

            // Dropdown toggles
            const dropdownToggles = document.querySelectorAll('.sidebar-dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    content.classList.toggle('hidden');
                });
            });

            // Auto-open dropdowns if active route is inside
            const adminRoutes = ['users', 'tournaments', 'locations', 'news'];
            const isAdminRouteActive = adminRoutes.some(route => window.location.pathname.includes('/admin/' + route));
            if (isAdminRouteActive) {
                const verwaltungDropdown = document.querySelector('.sidebar-dropdown-content');
                if (verwaltungDropdown) {
                    verwaltungDropdown.classList.remove('hidden');
                }
            }

            const headlinesRoutes = @json(\App\Models\Section::whereHas('headline')->pluck('section_name')->toArray());
            const isHeadlinesRouteActive = window.location.pathname.includes('/admin/headlines');
            if (isHeadlinesRouteActive) {
                const headlinesDropdown = document.querySelectorAll('.sidebar-dropdown-content')[1]; // Assuming second dropdown
                if (headlinesDropdown) {
                    headlinesDropdown.classList.remove('hidden');
                }
            }

            const viewsRoutes = @json(\App\Models\Section::pluck('section_name')->toArray());
            const isViewsRouteActive = viewsRoutes.some(route => window.location.pathname.includes('/admin/views/' + route));
            if (isViewsRouteActive) {
                const widgetsDropdown = document.querySelectorAll('.sidebar-dropdown-content')[2]; // Assuming third dropdown
                if (widgetsDropdown) {
                    widgetsDropdown.classList.remove('hidden');
                }
            }
        });
    </script>
    @stack('scripts')
</body>

</html>