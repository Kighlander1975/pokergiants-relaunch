<!-- resources/views/components/navbar.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top navimage">
    <div class="container-fluid myBlur">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="/images/logo.png" alt="Pokergiants.de" class="img-fluid navbar-logo">
            <span class="d-none d-md-inline ms-5 navbar-brand-text">Pokergiants.de</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menü</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center sm:py-2 min-h-[68px]" href="{{ route('home') }}">
                            <span class="mso me-2">home</span>
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center sm:py-2 min-h-[68px]" href="/about">
                            <span class="mso me-2">diversity_3</span>
                            Über uns
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center sm:py-2 min-h-[68px]" href="/contact">
                            <span class="mso me-2">chat</span>
                            Kontakt
                        </a>
                    </li>
                    @auth
                    @if(in_array(auth()->user()->userDetail->role ?? 'player', ['admin', 'floorman']))
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center sm:py-2" href="{{ route('dashboard') }}">
                            <span class="mso me-2">dashboard</span>
                            Dashboard
                        </a>
                    </li>
                    @endif
                    @auth
                    <li class="nav-item min-h-[68px]">
                        <a class="nav-link d-flex align-items-center px-2 sm:py-2" href="{{ route('profile.show') }}">
                            <x-avatar
                                :image-url="auth()->user()->getAvatarUrl('small')"
                                :firstname="auth()->user()->userDetail->firstname ?? null"
                                :lastname="auth()->user()->userDetail->lastname ?? null"
                                :nickname="auth()->user()->nickname"
                                size="48" />
                            <div class="ms-1">
                                @php
                                $countryFlag = auth()->user()->userDetail->country_flag ?? 'de_DE';
                                $parts = explode('_', $countryFlag);
                                $countryCode = strtolower($parts[0]);
                                $regionCode = isset($parts[1]) ? '-' . strtolower($parts[1]) : '';
                                $flagCode = $countryCode . $regionCode;
                                @endphp
                                <span class="fi fi-{{ $flagCode }} text-2xl"></span>
                            </div>
                        </a>
                    </li>
                    @endauth
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link d-flex align-items-center sm:py-2 min-h-[68px]">
                                <span class="mso me-2">logout</span>
                                Logout
                            </button>
                        </form>
                    </li>
                    @endauth
                    @guest
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center sm:py-2 min-h-[68px]" href="{{ route('verification.send') }}">
                            <span class="mso me-2">mail</span>
                            E-Mail verifizieren
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center sm:py-2 min-h-[68px]" href="{{ route('login') }}">
                            <span class="mso me-2">login</span>
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center sm:py-2 min-h-[68px]" href="{{ route('register') }}">
                            <span class="mso me-2">app_registration</span>
                            Registrieren
                        </a>
                    </li>
                    @endguest
                    <!-- Füge weitere Links hinzu -->
                </ul>
            </div>
        </div>
    </div>
</nav>