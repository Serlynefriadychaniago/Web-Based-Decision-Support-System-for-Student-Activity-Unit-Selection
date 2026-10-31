<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- FAVICON --}}
    <link rel="icon" type="image/png" href="{{ asset('images/login/logo.png') }}">

    <title>Masuk - SPK Pemilihan UKM UMC</title>

    {{-- Google Font Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="min-h-screen bg-[#f7f7fb] font-[Inter]">


<div class="min-h-screen flex">


    {{-- =====================================================
         BAGIAN KIRI
    ====================================================== --}}

    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">


        {{-- Background Kampus --}}

        <img
            src="{{ asset('images/login/bg.png') }}"
            alt="Universitas Muhammadiyah Cirebon"
            class="absolute inset-0 w-full h-full object-cover"
        >


        {{-- Overlay Ungu --}}

        <div
            class="absolute inset-0 bg-gradient-to-br
                   from-[#991b1b]/90
                   via-[#b91c1c]/75
                   to-[#da2424]/70">
        </div>


        {{-- Content Kiri --}}

        <div
            class="relative z-10
                   flex flex-col
                   justify-between
                   w-full
                   p-12
                   text-white">


            {{-- =================================================
                 LOGO
            ================================================== --}}

            <div>

                <img
                    src="{{ asset('images/login/logo.png') }}"
                    alt="Logo Universitas Muhammadiyah Cirebon"
                    class="w-24 h-24
                           object-contain
                           drop-shadow-lg"
                >

            </div>


            {{-- =================================================
                 TEXT
            ================================================== --}}

            <div class="max-w-xl mb-10">


                <p
                    class="text-sm
                           uppercase
                           tracking-[0.25em]
                           font-semibold
                           text-white/80
                           mb-4"
                >
                    Sistem Pendukung Keputusan
                </p>


                <h1
                    class="text-4xl
                           xl:text-5xl
                           font-extrabold
                           leading-tight
                           mb-5"
                >

                    Pemilihan UKM

                    <br>

                    Sesuai Minat

                    <br>

                    Mahasiswa

                </h1>


                <p
                    class="text-white/85
                           text-base
                           leading-relaxed
                           max-w-lg"
                >

                    Temukan Unit Kegiatan Mahasiswa yang paling sesuai
                    dengan minat dan bakatmu melalui sistem rekomendasi
                    berbasis AHP dan TOPSIS.

                </p>


            </div>


            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <div
                class="text-sm
                       text-white/70"
            >

                © {{ date('Y') }} Universitas Muhammadiyah Cirebon

            </div>


        </div>


    </div>



    {{-- =====================================================
         BAGIAN KANAN
    ====================================================== --}}

    <div
        class="w-full
               lg:w-1/2
               flex
               items-center
               justify-center
               px-6
               py-10"
    >


        <div class="w-full max-w-md">


            {{-- =================================================
                 LOGO MOBILE
            ================================================== --}}

            <div
                class="flex
                       justify-center
                       lg:hidden
                       mb-6"
            >

                <img
                    src="{{ asset('images/login/logo.png') }}"
                    alt="Logo Universitas Muhammadiyah Cirebon"
                    class="w-20 h-20 object-contain"
                >

            </div>



            {{-- =================================================
                 HEADING
            ================================================== --}}

            <div
                class="text-center
                       mb-8"
            >

                <h2
                    class="text-3xl
                           font-extrabold
                           text-[#1f2937]"
                >

                    Selamat Datang

                </h2>


                <p
                    class="mt-2
                           text-sm
                           text-gray-500"
                >

                    Masuk untuk mendapatkan rekomendasi UKM
                    yang sesuai dengan minat dan bakatmu

                </p>

            </div>



            {{-- =================================================
                 CARD LOGIN
            ================================================== --}}

            <div
                class="bg-white
                       rounded-3xl
                       shadow-xl
                       shadow-red-100/60
                       p-7
                       sm:p-8"
            >


                {{-- =================================================
                     SESSION STATUS
                ================================================== --}}

                @if (session('status'))

                    <div
                        class="mb-5
                               rounded-xl
                               bg-green-50
                               border
                               border-green-200
                               px-4
                               py-3
                               text-sm
                               text-green-700"
                    >

                        {{ session('status') }}

                    </div>

                @endif



                {{-- =================================================
                     ERROR LOGIN
                ================================================== --}}

                @if ($errors->any())

                    <div
                        class="mb-5
                               rounded-xl
                               bg-red-50
                               border
                               border-red-200
                               px-4
                               py-3"
                    >

                        <p
                            class="text-sm
                                   font-semibold
                                   text-red-700
                                   mb-1"
                        >

                            Login gagal

                        </p>


                        <ul
                            class="text-sm
                                   text-red-600
                                   list-disc
                                   list-inside"
                        >

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif



                {{-- =================================================
                     FORM
                ================================================== --}}

                <form
                    method="POST"
                    action="{{ route('login') }}"
                >

                    @csrf



                    {{-- =================================================
                         EMAIL
                    ================================================== --}}

                    <div>

                        <label
                            for="email"
                            class="block
                                   text-sm
                                   font-semibold
                                   text-gray-700
                                   mb-2"
                        >

                            Email

                        </label>


                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan email"
                            class="w-full
                                   rounded-xl
                                   border-gray-200
                                   bg-gray-50
                                   px-4
                                   py-3
                                   text-gray-800
                                   placeholder-gray-400
                                   focus:border-[#da2424]
                                   focus:ring-[#da2424]
                                   transition
                                   duration-200"
                        >


                        @if ($errors->get('email'))

                            <p
                                class="mt-2
                                       text-sm
                                       text-red-500"
                            >

                                {{ $errors->first('email') }}

                            </p>

                        @endif

                    </div>



                    {{-- =================================================
                         PASSWORD
                    ================================================== --}}

                    <div class="mt-5">

                        <label
                            for="password"
                            class="block
                                   text-sm
                                   font-semibold
                                   text-gray-700
                                   mb-2"
                        >

                            Password

                        </label>


                        {{-- Password wrapper --}}

                        <div class="relative">


                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="w-full
                                       rounded-xl
                                       border-gray-200
                                       bg-gray-50
                                       px-4
                                       py-3
                                       pr-12
                                       text-gray-800
                                       placeholder-gray-400
                                       focus:border-[#da2424]
                                       focus:ring-[#da2424]
                                       transition
                                       duration-200"
                            >


                            {{-- Tombol tampil/sembunyikan password --}}

                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute
                                       right-3
                                       top-1/2
                                       -translate-y-1/2
                                       w-9
                                       h-9
                                       flex
                                       items-center
                                       justify-center
                                       rounded-lg
                                       text-gray-400
                                       hover:text-[#da2424]
                                       hover:bg-red-50
                                       transition"
                                aria-label="Tampilkan password"
                            >


                                {{-- Icon eye --}}

                                <svg
                                    id="eyeIcon"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                    class="w-5 h-5"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.644
                                           C3.423 7.51 7.36 5 12 5
                                           c4.64 0 8.577 2.51 9.964 6.678
                                           a1.012 1.012 0 010 .644
                                           C20.577 16.49 16.64 19 12 19
                                           c-4.64 0-8.577-2.51-9.964-6.678z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0
                                           3 3 0 016 0z"
                                    />

                                </svg>


                            </button>


                        </div>


                        @if ($errors->get('password'))

                            <p
                                class="mt-2
                                       text-sm
                                       text-red-500"
                            >

                                {{ $errors->first('password') }}

                            </p>

                        @endif

                    </div>



                    {{-- =================================================
                         REMEMBER + FORGOT PASSWORD
                    ================================================== --}}

                    <div
                        class="flex
                               items-center
                               justify-between
                               mt-5"
                    >


                        {{-- Remember me --}}

                        <label
                            for="remember_me"
                            class="inline-flex
                                   items-center
                                   cursor-pointer"
                        >

                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="w-4
                                       h-4
                                       rounded
                                       border-gray-300
                                       text-[#da2424]
                                       shadow-sm
                                       focus:ring-[#da2424]"
                            >


                            <span
                                class="ms-2
                                       text-sm
                                       text-gray-500"
                            >

                                Ingat saya

                            </span>

                        </label>



                        {{-- Forgot password --}}

                        @if (Route::has('password.request'))

                            <a
                                href="{{ route('password.request') }}"
                                class="text-sm
                                       font-medium
                                       text-[#da2424]
                                       hover:text-[#b91c1c]
                                       transition"
                            >

                                Lupa password?

                            </a>

                        @endif


                    </div>



                    {{-- =================================================
                         BUTTON LOGIN
                    ================================================== --}}

                    <button
                        type="submit"
                        class="w-full
                               mt-7
                               py-3.5
                               px-5
                               rounded-xl
                               bg-gradient-to-r
                               from-[#da2424]
                               to-[#b91c1c]
                               text-white
                               font-semibold
                               shadow-lg
                               shadow-red-200
                               hover:shadow-red-300
                               hover:-translate-y-0.5
                               transition-all
                               duration-200"
                    >

                        Masuk

                    </button>



                    {{-- =================================================
                         DIVIDER ATAU
                    ================================================== --}}

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase">
                            <span class="bg-white px-3 text-gray-400 font-medium tracking-wider">
                                atau masuk dengan
                            </span>
                        </div>
                    </div>



                    {{-- =================================================
                         LOGIN DENGAN GOOGLE
                    ================================================== --}}

                    <a
                        href="{{ route('auth.google') }}"
                        class="w-full
                               py-3.5
                               px-5
                               rounded-xl
                               border
                               border-gray-200
                               bg-white
                               hover:bg-gray-50
                               hover:border-gray-300
                               text-gray-700
                               font-semibold
                               shadow-sm
                               hover:shadow
                               hover:-translate-y-0.5
                               transition-all
                               duration-200
                               flex
                               items-center
                               justify-center
                               gap-3"
                    >

                        {{-- Google SVG Icon Resmi --}}
                        <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>

                        <span>Login dengan Google</span>

                    </a>



                    {{-- =================================================
                         REGISTER
                    ================================================== --}}

                    @if (Route::has('register'))

                        <div
                            class="text-center
                                   mt-6"
                        >

                            <span
                                class="text-sm
                                       text-gray-500"
                            >

                                Belum memiliki akun?

                            </span>


                            <a
                                href="{{ route('register') }}"
                                class="text-sm
                                       font-semibold
                                       text-[#da2424]
                                       hover:text-[#b91c1c]
                                       transition"
                            >

                                Daftar sekarang

                            </a>

                        </div>

                    @endif


                </form>


            </div>



            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <p
                class="text-center
                       text-xs
                       text-gray-400
                       mt-6"
            >

                © {{ date('Y') }} Universitas Muhammadiyah Cirebon

            </p>


        </div>


    </div>


</div>



{{-- =========================================================
     JAVASCRIPT PASSWORD TOGGLE
========================================================= --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');


        if (!passwordInput || !toggleButton) {
            return;
        }


        toggleButton.addEventListener('click', function () {

            if (passwordInput.type === 'password') {

                passwordInput.type = 'text';

                toggleButton.setAttribute(
                    'aria-label',
                    'Sembunyikan password'
                );


                eyeIcon.innerHTML = `
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3.98 8.223A10.477 10.477 0 002.036 12.322
                           a1.012 1.012 0 000 .644
                           C3.423 17.49 7.36 20 12 20
                           c1.76 0 3.42-.43 4.88-1.19"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6.228 6.228A10.45 10.45 0 0112 4
                           c4.64 0 8.577 2.51 9.964 6.678
                           a1.012 1.012 0 010 .644
                           10.52 10.52 0 01-4.132 5.223"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6.228 6.228L3 3m3.228 3.228l3.65 3.65
                           m5.894 5.894L21 21
                           m-3.228-3.228l-3.65-3.65"
                    />
                `;

            } else {

                passwordInput.type = 'password';

                toggleButton.setAttribute(
                    'aria-label',
                    'Tampilkan password'
                );


                eyeIcon.innerHTML = `
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 010-.644
                           C3.423 7.51 7.36 5 12 5
                           c4.64 0 8.577 2.51 9.964 6.678
                           a1.012 1.012 0 010 .644
                           C20.577 16.49 16.64 19 12 19
                           c-4.64 0-8.577-2.51-9.964-6.678z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0
                           3 3 0 016 0z"
                    />
                `;

            }

        });

    });

</script>


</body>

</html>