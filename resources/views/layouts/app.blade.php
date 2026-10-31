<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- FAVICON --}}
    <link rel="icon" type="image/png" href="{{ asset('images/login/logo.png') }}">

    <title>
        @yield('title', 'SPK Pemilihan UKM')
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>[x-cloak]{display:none!important}.no-transition,.no-transition *{transition:none!important}</style>

    <script>
        (function() {
            var isCollapsed = localStorage.getItem('spk_sidebar_collapsed');
            if (isCollapsed === 'true' && window.innerWidth > 760) {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="app-body no-transition">

<div class="app-wrapper">
    <script>
        if (document.documentElement.classList.contains('sidebar-collapsed')) {
            var w = document.currentScript.parentElement;
            if (w) w.classList.add('sidebar-collapsed');
        }
    </script>

    {{-- =====================================================
        SIDEBAR
    ====================================================== --}}

    <aside class="sidebar">

        {{-- =================================================
            LOGO
        ================================================== --}}

        <div class="sidebar-brand">

            <div class="brand-logo">

                <img src="{{ asset('images/login/logo.png') }}"
                     alt="Logo UMC"
                     class="brand-logo-img">

            </div>

            <div class="brand-text">

                <div class="brand-title">
                    SPK PEMILIHAN UKM
                </div>

                <div class="brand-subtitle">
                    AHP & TOPSIS
                </div>

            </div>

        </div>


        {{-- =================================================
            MENU
        ================================================== --}}

        <div class="sidebar-content">

            {{-- =================================================
                ADMIN
            ================================================== --}}

            @if(auth()->user()->role === 'admin')

                <div class="menu-section-title">
                    MENU ADMIN
                </div>


                {{-- =================================================
                    DASHBOARD ADMIN
                ================================================== --}}

                <a href="{{ route('admin.dashboard') }}"
                   data-title="Dashboard"
                   class="sidebar-menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <path d="M3 10.5L12 3l9 7.5"></path>
                        <path d="M5 9.5V21h14V9.5"></path>
                        <path d="M9 21v-6h6v6"></path>
                    </svg>

                    <span>Dashboard</span>

                </a>


                {{-- =================================================
                    DATA UKM
                ================================================== --}}

                <a href="{{ route('ukm.index') }}"
                   data-title="Data UKM"
                   class="sidebar-menu {{ request()->routeIs('ukm.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                        <path d="M8 9h8"></path>
                        <path d="M8 13h8"></path>
                        <path d="M8 17h5"></path>
                    </svg>

                    <span>Data UKM</span>

                </a>


                {{-- =================================================
                    KRITERIA
                ================================================== --}}

                <a href="{{ route('kriteria.index') }}"
                   data-title="Kriteria"
                   class="sidebar-menu {{ request()->routeIs('kriteria.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="8"></circle>
                        <path d="M12 8v8"></path>
                        <path d="M8 12h8"></path>
                    </svg>

                    <span>Kriteria</span>

                </a>


                {{-- =================================================
                    SUB-KRITERIA
                ================================================== --}}

                <a href="{{ route('sub-kriteria.index') }}"
                   data-title="Sub-Kriteria"
                   class="sidebar-menu {{ request()->routeIs('sub-kriteria.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <path d="M8 6h13"></path>
                        <path d="M8 12h13"></path>
                        <path d="M8 18h13"></path>
                        <path d="M3 6h.01"></path>
                        <path d="M3 12h.01"></path>
                        <path d="M3 18h.01"></path>
                    </svg>

                    <span>Sub-Kriteria</span>

                </a>


                {{-- =================================================
                    PROFIL KRITERIA UKM
                ================================================== --}}

                <a href="{{ route('ukm-kriteria-profile.index') }}"
                   data-title="Profil Kriteria UKM"
                   class="sidebar-menu {{ request()->routeIs('ukm-kriteria-profile.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                        <path d="M8 8h8"></path>
                        <path d="M8 12h5"></path>
                        <path d="M8 16h8"></path>
                    </svg>

                    <span>Profil Kriteria UKM</span>

                </a>


                {{-- =================================================
                    AHP
                ================================================== --}}

                <a href="{{ route('ahp.index') }}"
                   data-title="Perhitungan AHP"
                   class="sidebar-menu {{ request()->routeIs('ahp.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <path d="M4 19V5"></path>
                        <path d="M4 19h16"></path>
                        <path d="M7 16l4-5 3 2 5-7"></path>
                    </svg>

                    <span>Perhitungan AHP</span>

                </a>


                {{-- =================================================
                    TOPSIS
                ================================================== --}}

                <a href="{{ route('topsis.index') }}"
                   data-title="Hasil TOPSIS"
                   class="sidebar-menu {{ request()->routeIs('topsis.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <path d="M4 19V5"></path>
                        <path d="M4 19h16"></path>
                        <path d="M7 15l3-3 3 2 5-6"></path>
                    </svg>

                    <span>Hasil TOPSIS</span>

                </a>


                {{-- =================================================
                    DATA MAHASISWA
                ================================================== --}}

                <a href="{{ route('admin.mahasiswa.index') }}"
                   data-title="Data Mahasiswa"
                   class="sidebar-menu {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="3"></circle>
                        <path d="M5 21c0-3.5 3-6 7-6s7 2.5 7 6"></path>
                    </svg>

                    <span>Data Mahasiswa</span>

                </a>


                {{-- =================================================
                    PENDAFTARAN UKM
                ================================================== --}}

                <a href="{{ route('admin.pendaftaran.index') }}"
                   data-title="Pendaftaran UKM"
                   class="sidebar-menu {{ request()->routeIs('admin.pendaftaran.index') || request()->routeIs('admin.pendaftaran.show') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <path d="M6 3h12v18H6z"></path>
                        <path d="M9 8h6"></path>
                        <path d="M9 12h6"></path>
                        <path d="M9 16h4"></path>
                    </svg>

                    <span>Pendaftaran UKM</span>

                </a>

                <a href="{{ route('admin.laporan.pendaftaran.index') }}"
                   data-title="Laporan Pendaftaran"
                   class="sidebar-menu {{ request()->routeIs('admin.laporan.pendaftaran.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>

                    <span>Laporan Pendaftaran</span>

                </a>

                {{-- =================================================
                    REVIEW UKM
                ================================================== --}}

                <a href="{{ route('admin.reviews.index') }}"
                   data-title="Review UKM"
                   class="sidebar-menu {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>

                    <span>Review UKM</span>

                </a>


                {{-- =================================================
                    ANALISIS DATA
                ================================================== --}}

                <a href="{{ route('admin.analisis.index') }}"
                   data-title="Analisis Data"
                   class="sidebar-menu {{ request()->routeIs('admin.analisis.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>

                    <span>Analisis Data</span>

                </a>


            {{-- =================================================
                MAHASISWA
            ================================================== --}}

            @elseif(auth()->user()->role === 'mahasiswa')

                <div class="menu-section-title">
                    MENU MAHASISWA
                </div>


                {{-- =================================================
                    DASHBOARD MAHASISWA
                ================================================== --}}

                <a href="{{ route('mahasiswa.dashboard') }}"
                   data-title="Dashboard"
                   class="sidebar-menu {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <path d="M3 10.5L12 3l9 7.5"></path>
                        <path d="M5 9.5V21h14V9.5"></path>
                        <path d="M9 21v-6h6v6"></path>
                    </svg>

                    <span>Dashboard</span>

                </a>


                {{-- =================================================
                    DATA UKM MAHASISWA
                ================================================== --}}

                <a href="{{ route('mahasiswa.ukm.index') }}"
                   data-title="Data UKM"
                   class="sidebar-menu {{ request()->routeIs('mahasiswa.ukm.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V7l7-4 7 4v14"></path>
                        <path d="M9 10h6"></path>
                        <path d="M9 14h6"></path>
                        <path d="M9 18h6"></path>
                    </svg>

                    <span>Data UKM</span>

                </a>


                {{-- =================================================
                    KUESIONER
                ================================================== --}}

                <a href="{{ route('mahasiswa.kuesioner.index') }}"
                   data-title="Kuesioner"
                   class="sidebar-menu {{ request()->routeIs('mahasiswa.kuesioner.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <rect x="5" y="4" width="14" height="17" rx="2"></rect>
                        <path d="M8 8h8"></path>
                        <path d="M8 12h8"></path>
                        <path d="M8 16h5"></path>
                    </svg>

                    <span>Kuesioner</span>

                </a>


                {{-- =================================================
                    HASIL REKOMENDASI
                ================================================== --}}

                <a href="{{ route('mahasiswa.rekomendasi.index') }}"
                   data-title="Hasil Rekomendasi"
                   class="sidebar-menu {{ request()->routeIs('mahasiswa.rekomendasi.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <path d="M4 19V5"></path>
                        <path d="M4 19h16"></path>
                        <path d="M7 15l3-4 3 2 5-6"></path>
                    </svg>

                    <span>Hasil Rekomendasi</span>

                </a>


                {{-- =================================================
                    PENDAFTARAN SAYA
                ================================================== --}}

                <a href="{{ route('mahasiswa.pendaftaran.index') }}"
                   data-title="Pendaftaran Saya"
                   class="sidebar-menu {{ request()->routeIs('mahasiswa.pendaftaran.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <path d="M6 3h12v18H6z"></path>
                        <path d="M9 8h6"></path>
                        <path d="M9 12h6"></path>
                        <path d="M9 16h4"></path>
                    </svg>

                    <span>Pendaftaran Saya</span>

                </a>


                {{-- =================================================
                    PROFIL
                ================================================== --}}

                <a href="{{ route('profile.edit') }}"
                   data-title="Profil"
                   class="sidebar-menu {{ request()->routeIs('profile.*') ? 'active' : '' }}">

                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="3"></circle>
                        <path d="M5 21c0-3.5 3-6 7-6s7 2.5 7 6"></path>
                    </svg>

                    <span>Profil</span>

                </a>

            @endif

        </div>


        {{-- =====================================================
            ILUSTRASI BAWAH
        ====================================================== --}}

        <div class="sidebar-bottom">

            <div class="sidebar-quote">

                <div class="quote-mark">
                    “
                </div>

                <p>

                    {{ auth()->user()->role === 'admin'
                        ? 'Kelola data dan proses sistem pendukung keputusan dengan mudah.'
                        : 'Jawaban Anda akan membantu kami memberikan rekomendasi UKM yang paling sesuai dengan diri Anda.'
                    }}

                </p>

                <div class="quote-mark quote-right">
                    ”
                </div>

            </div>

        </div>

    </aside>

    {{-- =====================================================
        MOBILE OVERLAY
    ====================================================== --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity"></div>


    {{-- =====================================================
        AREA KANAN
    ====================================================== --}}

    <div class="main-area">


        {{-- =================================================
            TOPBAR
        ================================================== --}}

        <header class="topbar">

            <div class="topbar-left flex items-center gap-3">

                <button
                    type="button"
                    id="sidebarToggleBtn"
                    class="sidebar-toggle-btn"
                    aria-label="Toggle Sidebar"
                    title="Collapse / Expand Sidebar">

                    <svg class="toggle-icon" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                        <path d="M14 9l-3 3 3 3"></path>
                    </svg>

                </button>

                <h2>
                    @yield('page-title', 'Dashboard')
                </h2>

            </div>


            <div class="topbar-right">

                {{-- USER PROFILE (CLICKABLE TO PROFIL SAYA) --}}

                <a href="{{ route('profile.edit') }}"
                   class="user-profile hover:opacity-90 transition cursor-pointer"
                   title="Lihat & Kelola Profil Saya">

                    <div class="user-avatar">

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                    </div>


                    <div class="user-info">

                        <div class="user-name">
                            {{ auth()->user()->name }}
                        </div>

                        <div class="user-role">
                            {{ ucfirst(auth()->user()->role) }}
                        </div>

                    </div>

                </a>


                {{-- LOGOUT --}}

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="ml-3">

                    @csrf

                    <button
                        type="submit"
                        class="text-sm text-gray-500 hover:text-red-600 transition">

                        Logout

                    </button>

                </form>

            </div>

        </header>


        {{-- =================================================
            CONTENT
        ================================================== --}}

        <main class="page-content">

            @yield('content')

        </main>

    </div>

</div>

<script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            document.body.classList.remove('no-transition');
        }, 50);
    });

    document.addEventListener('DOMContentLoaded', function() {
        var toggleBtn = document.getElementById('sidebarToggleBtn');
        var wrapper = document.querySelector('.app-wrapper');
        var htmlEl = document.documentElement;
        var overlay = document.getElementById('sidebar-overlay');

        if (htmlEl.classList.contains('sidebar-collapsed') && wrapper && window.innerWidth > 768) {
            wrapper.classList.add('sidebar-collapsed');
        }

        if (toggleBtn && wrapper) {
            toggleBtn.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    // Mobile toggle behavior
                    wrapper.classList.toggle('mobile-sidebar-open');
                    if (overlay) overlay.classList.toggle('hidden');
                } else {
                    // Desktop toggle behavior
                    var isCollapsed = wrapper.classList.toggle('sidebar-collapsed');
                    htmlEl.classList.toggle('sidebar-collapsed', isCollapsed);
                    localStorage.setItem('spk_sidebar_collapsed', isCollapsed ? 'true' : 'false');
                }
            });
        }

        // Close sidebar when overlay is clicked on mobile
        if (overlay) {
            overlay.addEventListener('click', function() {
                wrapper.classList.remove('mobile-sidebar-open');
                overlay.classList.add('hidden');
            });
        }
    });
</script>

</body>
</html>