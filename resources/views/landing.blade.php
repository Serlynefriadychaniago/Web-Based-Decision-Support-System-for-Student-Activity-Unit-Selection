<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    {{-- FAVICON --}}
    <link rel="icon" type="image/png" href="{{ asset('images/login/logo.png') }}">

    <title>
        SPK Pemilihan UKM | Universitas Muhammadiyah Cirebon
    </title>

    {{-- =====================================================
         GOOGLE FONT - INTER
    ====================================================== --}}
    <link rel="preconnect"
        href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">


    {{-- =====================================================
         CSS
    ====================================================== --}}
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
        }


        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: #ffffff;
            color: #1f2937;
        }


        /* =====================================================
           LANDING HERO
        ====================================================== */

        .hero {
            position: relative;
            min-height: 100vh;

            background-image:
                linear-gradient(
                    90deg,
                    rgba(95, 0, 0, 0.94) 0%,
                    rgba(119, 0, 0, 0.82) 42%,
                    rgba(139, 0, 0, 0.50) 100%
                ),
                url('{{ asset('images/landing/bg.png') }}');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            display: flex;
            flex-direction: column;

            overflow: hidden;
        }


        /* =====================================================
           NAVBAR
        ====================================================== */

        .navbar {
            width: 100%;

            padding: 24px 7%;

            display: flex;
            align-items: center;
            justify-content: space-between;

            position: relative;
            z-index: 10;
        }


        .brand {
            display: flex;
            align-items: center;
            gap: 14px;

            text-decoration: none;
            color: white;
        }


        .brand-logo {
            width: 52px;
            height: 52px;

            object-fit: contain;

            background: rgba(255,255,255,0.95);

            border-radius: 12px;

            padding: 5px;

            box-shadow:
                0 8px 25px rgba(0,0,0,0.15);
        }


        .brand-text {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }


        .brand-title {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -0.2px;
        }


        .brand-subtitle {
            font-size: 11px;
            font-weight: 400;
            opacity: 0.82;
        }


        .nav-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
        }


        .btn-login {
            padding: 11px 22px;

            border-radius: 10px;

            color: white;

            text-decoration: none;

            font-size: 13px;
            font-weight: 600;

            border: 1px solid rgba(255,255,255,0.55);

            background: rgba(255,255,255,0.08);

            backdrop-filter: blur(8px);

            transition: all 0.25s ease;
        }


        .btn-login:hover {
            background: rgba(255,255,255,0.18);

            transform: translateY(-1px);
        }


        .btn-register {
            padding: 12px 22px;

            border-radius: 10px;

            color: #da2424;

            text-decoration: none;

            font-size: 13px;
            font-weight: 700;

            background: white;

            box-shadow:
                0 8px 25px rgba(0,0,0,0.15);

            transition: all 0.25s ease;
        }


        .btn-register:hover {
            transform: translateY(-2px);

            box-shadow:
                0 12px 30px rgba(0,0,0,0.20);
        }


        /* =====================================================
           HERO CONTENT
        ====================================================== */

        .hero-content {
            width: 86%;
            max-width: 1200px;

            margin: auto;

            padding: 60px 0 90px;

            position: relative;
            z-index: 5;

            display: flex;
            align-items: center;
        }


        .hero-left {
            width: 62%;

            color: white;
        }


        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 8px 14px;

            margin-bottom: 22px;

            border-radius: 999px;

            background: rgba(255,255,255,0.13);

            border: 1px solid rgba(255,255,255,0.22);

            backdrop-filter: blur(8px);

            font-size: 12px;
            font-weight: 600;

            letter-spacing: 0.2px;
        }


        .badge-dot {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: #fecaca;

            box-shadow:
                0 0 10px rgba(217,200,255,0.9);
        }


        .hero-title {
            font-size: clamp(42px, 5vw, 68px);

            line-height: 1.08;

            letter-spacing: -2.5px;

            font-weight: 800;

            max-width: 760px;

            margin-bottom: 24px;
        }


        .hero-title span {
            color: #fecaca;
        }


        .hero-description {
            max-width: 650px;

            font-size: 16px;

            line-height: 1.8;

            color: rgba(255,255,255,0.86);

            margin-bottom: 34px;
        }


        /* =====================================================
           CTA
        ====================================================== */

        .hero-actions {
            display: flex;
            align-items: center;

            gap: 14px;

            margin-bottom: 35px;
        }


        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 14px 24px;

            border-radius: 11px;

            background: white;

            color: #da2424;

            text-decoration: none;

            font-size: 14px;
            font-weight: 700;

            box-shadow:
                0 10px 30px rgba(0,0,0,0.18);

            transition: all 0.25s ease;
        }


        .btn-primary:hover {
            transform: translateY(-2px);

            box-shadow:
                0 15px 35px rgba(0,0,0,0.23);
        }


        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 13px 24px;

            border-radius: 11px;

            background: rgba(255,255,255,0.08);

            border: 1px solid rgba(255,255,255,0.45);

            color: white;

            text-decoration: none;

            font-size: 14px;
            font-weight: 600;

            backdrop-filter: blur(8px);

            transition: all 0.25s ease;
        }


        .btn-secondary:hover {
            background: rgba(255,255,255,0.16);

            transform: translateY(-2px);
        }


        /* =====================================================
           TRUST TEXT
        ====================================================== */

        .hero-note {
            display: flex;
            align-items: center;
            gap: 10px;

            font-size: 12px;

            color: rgba(255,255,255,0.70);
        }


        .hero-note-icon {
            width: 22px;
            height: 22px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: rgba(255,255,255,0.12);

            font-size: 11px;
        }


        /* =====================================================
           FLOATING INFO CARD
        ====================================================== */

        .hero-card {
            position: absolute;

            right: 2%;

            bottom: 12%;

            width: 290px;

            padding: 24px;

            border-radius: 20px;

            background: rgba(255,255,255,0.13);

            border: 1px solid rgba(255,255,255,0.25);

            backdrop-filter: blur(18px);

            color: white;

            box-shadow:
                0 25px 70px rgba(0,0,0,0.20);
        }


        .hero-card-label {
            font-size: 11px;

            text-transform: uppercase;

            letter-spacing: 1px;

            color: rgba(255,255,255,0.65);

            margin-bottom: 10px;
        }


        .hero-card-title {
            font-size: 19px;

            line-height: 1.4;

            font-weight: 700;

            margin-bottom: 15px;
        }


        .hero-card-line {
            height: 1px;

            background: rgba(255,255,255,0.18);

            margin: 15px 0;
        }


        .hero-card-text {
            font-size: 12px;

            line-height: 1.7;

            color: rgba(255,255,255,0.76);
        }


        /* =====================================================
           BOTTOM FEATURES
        ====================================================== */

        .features {
            width: 100%;

            background: white;

            padding: 55px 7%;
        }


        .features-container {
            max-width: 1200px;

            margin: auto;

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 25px;
        }


        .feature-card {
            padding: 28px;

            border-radius: 18px;

            background: #ffffff;

            border: 1px solid #fecaca;

            box-shadow:
                0 8px 30px rgba(72, 51, 110, 0.06);

            transition: all 0.25s ease;
        }


        .feature-card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 14px 35px rgba(72, 51, 110, 0.10);
        }


        .feature-icon {
            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 13px;

            background: #fef2f2;

            color: #da2424;

            font-size: 18px;

            margin-bottom: 18px;
        }


        .feature-title {
            font-size: 16px;

            font-weight: 700;

            color: #302747;

            margin-bottom: 9px;
        }


        .feature-text {
            font-size: 13px;

            line-height: 1.7;

            color: #6b7280;
        }


        /* =====================================================
           FOOTER
        ====================================================== */

        .footer {
            padding: 25px 7%;

            background: #fdf2f2;

            border-top: 1px solid #fecaca;

            text-align: center;

            font-size: 12px;

            color: #817a8e;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 900px) {

            .hero-content {
                width: 88%;
            }


            .hero-left {
                width: 100%;
            }


            .hero-card {
                display: none;
            }


            .hero-title {
                font-size: 48px;
            }


            .features-container {
                grid-template-columns:
                    1fr;
            }
        }


        @media (max-width: 600px) {

            .navbar {
                padding: 18px 5%;
            }


            .brand-logo {
                width: 43px;
                height: 43px;
            }


            .brand-title {
                font-size: 12px;
            }


            .brand-subtitle {
                font-size: 9px;
            }


            .nav-buttons {
                gap: 7px;
            }


            .btn-login,
            .btn-register {
                padding: 9px 13px;

                font-size: 11px;
            }


            .hero-content {
                padding-top: 40px;

                width: 90%;
            }


            .hero-title {
                font-size: 39px;

                letter-spacing: -1.5px;
            }


            .hero-description {
                font-size: 14px;

                line-height: 1.7;
            }


            .hero-actions {
                flex-direction: column;

                align-items: stretch;
            }


            .btn-primary,
            .btn-secondary {
                width: 100%;
            }


            .features {
                padding: 40px 5%;
            }
        }

    </style>

</head>


<body>


    {{-- =====================================================
         HERO / LANDING PAGE
    ====================================================== --}}

    <section class="hero">


        {{-- =================================================
             NAVBAR
        ================================================== --}}

        <nav class="navbar">


            {{-- LOGO + BRAND --}}

            <a href="{{ route('landing') }}"
                class="brand">

                <img
                    src="{{ asset('images/landing/logo.png') }}"
                    alt="Logo Universitas Muhammadiyah Cirebon"
                    class="brand-logo"
                >

                <div class="brand-text">

                    <div class="brand-title">
                        Universitas Muhammadiyah Cirebon
                    </div>

                    <div class="brand-subtitle">
                        Sistem Pendukung Keputusan Pemilihan UKM
                    </div>

                </div>

            </a>


            {{-- NAV BUTTONS --}}

            <div class="nav-buttons">

                <a
                    href="{{ route('login') }}"
                    class="btn-login"
                >
                    Masuk
                </a>


                @if (Route::has('register'))

                    <a
                        href="{{ route('register') }}"
                        class="btn-register"
                    >
                        Daftar
                    </a>

                @endif

            </div>

        </nav>



        {{-- =================================================
             HERO CONTENT
        ================================================== --}}

        <div class="hero-content">


            <div class="hero-left">


                {{-- BADGE --}}

                <div class="badge">

                    <span class="badge-dot"></span>

                    Sistem Informasi Pemilihan UKM

                </div>


                {{-- TITLE --}}

                <h1 class="hero-title">

                    Temukan UKM yang
                    <span>
                        paling sesuai
                    </span>
                    dengan minatmu.

                </h1>


                {{-- DESCRIPTION --}}

                <p class="hero-description">

                    Sistem Pendukung Keputusan Pemilihan Unit Kegiatan
                    Mahasiswa Universitas Muhammadiyah Cirebon yang
                    membantu mahasiswa menemukan pilihan UKM berdasarkan
                    minat, bakat, jadwal, prestasi, dan biaya.

                </p>


                {{-- BUTTONS --}}

                <div class="hero-actions">


                    <a
                        href="{{ route('login') }}"
                        class="btn-primary"
                    >

                        Mulai Memilih UKM
                        &nbsp; →

                    </a>


                    @if (Route::has('register'))

                        <a
                            href="{{ route('register') }}"
                            class="btn-secondary"
                        >

                            Buat Akun

                        </a>

                    @endif

                </div>


                {{-- NOTE --}}

                <div class="hero-note">

                    <span class="hero-note-icon">
                        ✓
                    </span>

                    Rekomendasi berdasarkan preferensi mahasiswa

                </div>


            </div>



            {{-- =================================================
                 FLOATING CARD
            ================================================== --}}

            <div class="hero-card">


                <div class="hero-card-label">
                    SPK UKM
                </div>


                <div class="hero-card-title">

                    Pilihan UKM yang lebih
                    terarah dan sesuai.

                </div>


                <div class="hero-card-line"></div>


                <div class="hero-card-text">

                    Sistem menggunakan metode
                    AHP dan TOPSIS untuk membantu
                    menghasilkan rekomendasi UKM
                    berdasarkan preferensi mahasiswa.

                </div>


            </div>


        </div>


    </section>



    {{-- =====================================================
         FEATURES
    ====================================================== --}}

    <section class="features">


        <div class="features-container">


            {{-- FEATURE 1 --}}

            <div class="feature-card">

                <div class="feature-icon">
                    ♡
                </div>


                <div class="feature-title">
                    Sesuai Minat & Bakat
                </div>


                <div class="feature-text">

                    Pilih UKM yang lebih sesuai dengan
                    minat dan bakat yang kamu miliki.

                </div>

            </div>



            {{-- FEATURE 2 --}}

            <div class="feature-card">

                <div class="feature-icon">
                    ◈
                </div>


                <div class="feature-title">
                    Rekomendasi Terukur
                </div>


                <div class="feature-text">

                    Sistem membantu memberikan rekomendasi
                    berdasarkan perhitungan AHP dan TOPSIS.

                </div>

            </div>



            {{-- FEATURE 3 --}}

            <div class="feature-card">

                <div class="feature-icon">
                    ✓
                </div>


                <div class="feature-title">
                    Mudah Digunakan
                </div>


                <div class="feature-text">

                    Isi preferensi, lihat hasil rekomendasi,
                    lalu tentukan UKM pilihanmu.

                </div>

            </div>


        </div>


    </section>



    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <footer class="footer">

        © {{ date('Y') }}
        Universitas Muhammadiyah Cirebon.
        Sistem Pendukung Keputusan Pemilihan UKM.

    </footer>


</body>

</html>