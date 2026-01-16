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

<body class="antialiased backend-body"
      data-headlines-routes="{{ \App\Models\Section::whereHas('headline')->pluck('section_name')->toJson() }}"
      data-views-routes="{{ \App\Models\Section::pluck('section_name')->toJson() }}"
      data-current-section="{{ request()->route('section') ?: (request()->route('headline') ? request()->route('headline')->section->section_name : (request()->get('section') ?: '')) }}"
      data-current-area="{{ request()->routeIs('admin.headlines.*') ? 'headlines' : (request()->routeIs('admin.widgets.*') ? 'widgets' : '') }}">
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

                <!-- Views Dropdown -->
                <div class="sidebar-dropdown">
                    <button class="sidebar-dropdown-toggle flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 w-full text-left">
                        <svg class="w-5 h-5 mr-3 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="sidebar-text">Views</span>
                        <svg class="w-4 h-4 ml-auto sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="sidebar-dropdown-content hidden pl-4">
                        <!-- Views Übersicht -->
                        <a href="{{ route('admin.views.index') }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.views.index') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="sidebar-text">Übersicht</span>
                        </a>

                        <!-- Neue Sektion -->
                        <a href="{{ route('admin.sections.create') }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="sidebar-text">Neue Sektion</span>
                        </a>

                        <!-- Home Section -->
                        <div class="sidebar-dropdown">
                            <button class="sidebar-dropdown-toggle flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 w-full text-left">
                                <span class="sidebar-text">Home</span>
                                <svg class="w-4 h-4 ml-auto sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="sidebar-dropdown-content hidden pl-4">
                                <!-- Headlines -->
                                @php $homeSection = \App\Models\Section::where('section_name', 'home')->first() @endphp
                                @if($homeSection && $homeSection->headline)
                                <a href="{{ route('admin.headlines.edit', $homeSection->headline) }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.headlines.edit') && request()->route('headline')->id == $homeSection->headline->id ? 'bg-gray-700 text-white' : '' }}">
                                    <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <span class="sidebar-text">Headlines</span>
                                </a>
                                @endif

                                <!-- Widgets Submenu -->
                                <div class="sidebar-dropdown">
                                    <button class="sidebar-dropdown-toggle flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 w-full text-left">
                                        <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span class="sidebar-text">Widgets</span>
                                        <svg class="w-4 h-4 ml-auto sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div class="sidebar-dropdown-content hidden pl-4">
                                        <a href="{{ route('admin.widgets.index', 'home') }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.widgets.index') && request()->route('section') === 'home' ? 'bg-gray-700 text-white' : '' }}">
                                            <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <span class="sidebar-text">Übersicht</span>
                                        </a>
                                        <a href="{{ route('admin.widgets.create', ['section' => 'home']) }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.widgets.create') && request()->route('section') === 'home' ? 'bg-gray-700 text-white' : '' }}">
                                            <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <span class="sidebar-text">Neues Widget</span>
                                        </a>
                                        @if($homeSection)
                                            @foreach($homeSection->widgets()->ordered()->get() as $widget)
                                            <a href="{{ route('admin.widgets.edit', ['home', $widget]) }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.widgets.edit') && request()->route('widget')?->id == $widget->id ? 'bg-gray-700 text-white' : '' }}">
                                                <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                                <span class="sidebar-text">{{ $widget->internal_name ?: 'Widget #' . $widget->id }}</span>
                                            </a>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Sections -->
                        @foreach(\App\Models\Section::where('section_name', '!=', 'home')->get() as $section)
                        <div class="sidebar-dropdown">
                            <button class="sidebar-dropdown-toggle flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 w-full text-left">
                                <span class="sidebar-text">{{ ucfirst($section->section_name) }}</span>
                                <svg class="w-4 h-4 ml-auto sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="sidebar-dropdown-content hidden pl-4">
                                <!-- Headlines -->
                                @if($section->headline)
                                <a href="{{ route('admin.headlines.edit', $section->headline) }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.headlines.edit') && request()->route('headline')->id == $section->headline->id ? 'bg-gray-700 text-white' : '' }}">
                                    <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <span class="sidebar-text">Headlines</span>
                                </a>
                                @endif

                                <!-- Widgets Submenu -->
                                <div class="sidebar-dropdown">
                                    <button class="sidebar-dropdown-toggle flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 w-full text-left">
                                        <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span class="sidebar-text">Widgets</span>
                                        <svg class="w-4 h-4 ml-auto sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div class="sidebar-dropdown-content hidden pl-4">
                                        <a href="{{ route('admin.widgets.index', $section->section_name) }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.widgets.index') && request()->route('section') === $section->section_name ? 'bg-gray-700 text-white' : '' }}">
                                            <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <span class="sidebar-text">Übersicht</span>
                                        </a>
                                        <a href="{{ route('admin.widgets.create', ['section' => $section->section_name]) }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.widgets.create') && request()->route('section') === $section->section_name ? 'bg-gray-700 text-white' : '' }}">
                                            <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <span class="sidebar-text">Neues Widget</span>
                                        </a>
                                        @foreach($section->widgets()->ordered()->get() as $widget)
                                        <a href="{{ route('admin.widgets.edit', [$section->section_name, $widget]) }}" class="sidebar-link flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.widgets.edit') && request()->route('widget')?->id == $widget->id ? 'bg-gray-700 text-white' : '' }}">
                                            <svg class="w-4 h-4 mr-2 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            <span class="sidebar-text">{{ $widget->internal_name ?: 'Widget #' . $widget->id }}</span>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
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

            // Auto-open Views dropdown for views-related routes
            const viewsRoutes = ['/admin/views', '/admin/sections/create', '/admin/sections', '/admin/headlines', '/admin/widgets'];
            const isViewsRouteActive = viewsRoutes.some(route => window.location.pathname.startsWith(route));
            if (isViewsRouteActive) {
                // Find the Views dropdown (the one containing "Übersicht", "Neue Sektion", etc.)
                const allDropdowns = document.querySelectorAll('.sidebar-dropdown-content');
                let viewsDropdown = null;
                allDropdowns.forEach(dropdown => {
                    if (dropdown.querySelector('[href*="admin/views"]') || dropdown.querySelector('[href*="admin/sections/create"]')) {
                        viewsDropdown = dropdown;
                    }
                });
                if (viewsDropdown) {
                    viewsDropdown.classList.remove('hidden');

                    // Get current section and area from data attributes
                    const currentSection = document.body.dataset.currentSection;
                    const currentArea = document.body.dataset.currentArea;

                    if (currentSection) {
                        // Find and open the specific section dropdown
                        const sectionButtons = viewsDropdown.querySelectorAll('.sidebar-dropdown-toggle');
                        sectionButtons.forEach(button => {
                            const buttonText = button.textContent.trim().toLowerCase();
                            if (buttonText === currentSection.toLowerCase()) {
                                const sectionContent = button.nextElementSibling;
                                if (sectionContent && sectionContent.classList.contains('sidebar-dropdown-content')) {
                                    sectionContent.classList.remove('hidden');

                                    // If we're in a specific area (headlines or widgets), open the corresponding submenu
                                    if (currentArea) {
                                        const submenuButtons = sectionContent.querySelectorAll('.sidebar-dropdown-toggle');
                                        submenuButtons.forEach(submenuButton => {
                                            const submenuText = submenuButton.textContent.trim().toLowerCase();
                                            if (submenuText.includes(currentArea)) {
                                                const submenuContent = submenuButton.nextElementSibling;
                                                if (submenuContent && submenuContent.classList.contains('sidebar-dropdown-content')) {
                                                    submenuContent.classList.remove('hidden');
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                        });
                    }
                }
            }
        });
    </script>
    @stack('scripts')
</body>

</html>