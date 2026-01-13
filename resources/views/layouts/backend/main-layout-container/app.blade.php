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

                <a href="{{ route('admin.users') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.users*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <span class="sidebar-text">Benutzer</span>
                </a>

                <a href="{{ route('admin.tournaments.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.tournaments*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="sidebar-text">Turniere</span>
                </a>

                <a href="{{ route('admin.locations.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.locations*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3-3 3 3m0 6l-3 3-3-3"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v0"></path>
                    </svg>
                    <span class="sidebar-text">Locations</span>
                </a>

                <a href="{{ route('admin.news.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.news*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h14v14H5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6v2H9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6v2H9z"></path>
                    </svg>
                    <span class="sidebar-text">News</span>
                </a>

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
        });
    </script>
    @stack('scripts')
</body>

</html>