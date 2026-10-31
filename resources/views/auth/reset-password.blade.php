<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    {{-- FAVICON --}}
    <link rel="icon" type="image/png" href="{{ asset('images/login/logo.png') }}">

    <title>
        Reset Password - SPK Pemilihan UKM UMC
    </title>


    {{-- =====================================================
         GOOGLE FONT INTER
    ====================================================== --}}

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body
    class="min-h-screen
           bg-[#f7f7fb]
           font-[Inter]"
>


<div class="min-h-screen flex">


    {{-- =====================================================
         BAGIAN KIRI
    ====================================================== --}}

    <div
        class="hidden
               lg:flex
               lg:w-1/2
               relative
               overflow-hidden"
    >


        {{-- BACKGROUND KAMPUS --}}

        <img
            src="{{ asset('images/login/bg.png') }}"
            alt="Universitas Muhammadiyah Cirebon"
            class="absolute
                   inset-0
                   w-full
                   h-full
                   object-cover"
        >


        {{-- OVERLAY --}}

        <div
            class="absolute
                   inset-0
                   bg-gradient-to-br
                    from-[#991b1b]/90
                    via-[#b91c1c]/75
                    to-[#da2424]/70"
        >
        </div>


        {{-- CONTENT --}}

        <div
            class="relative
                   z-10
                   flex
                   flex-col
                   justify-between
                   w-full
                   p-12
                   text-white"
        >


            {{-- LOGO --}}

            <div>

                <img
                    src="{{ asset('images/login/logo.png') }}"
                    alt="Logo Universitas Muhammadiyah Cirebon"
                    class="w-24
                           h-24
                           object-contain
                           drop-shadow-lg"
                >

            </div>


            {{-- TEXT --}}

            <div
                class="max-w-xl
                       mb-10"
            >

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
                    Temukan Unit Kegiatan Mahasiswa
                    yang paling sesuai dengan minat
                    dan bakatmu melalui sistem
                    rekomendasi berbasis AHP dan TOPSIS.
                </p>

            </div>


            {{-- FOOTER --}}

            <div
                class="text-sm
                       text-white/70"
            >

                © {{ date('Y') }}
                Universitas Muhammadiyah Cirebon

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


        <div
            class="w-full
                   max-w-md"
        >


            {{-- LOGO MOBILE --}}

            <div
                class="flex
                       justify-center
                       lg:hidden
                       mb-6"
            >

                <img
                    src="{{ asset('images/login/logo.png') }}"
                    alt="Logo Universitas Muhammadiyah Cirebon"
                    class="w-20
                           h-20
                           object-contain"
                >

            </div>



            {{-- HEADING --}}

            <div
                class="text-center
                       mb-8"
            >

                <h2
                    class="text-3xl
                           font-extrabold
                           text-gray-800"
                >
                    Buat Password Baru
                </h2>


                <p
                    class="mt-2
                           text-sm
                           text-gray-500"
                >
                    Silakan masukkan password baru
                    untuk akun Anda.
                </p>

            </div>



            {{-- CARD FORM --}}

            <div
                class="bg-white
                       rounded-3xl
                       shadow-xl
                       shadow-red-100/60
                       p-7
                       sm:p-8"
            >


                {{-- ERROR --}}

                @if ($errors->any())

                    <div
                        class="mb-5
                               rounded-xl
                               bg-red-50
                               border
                               border-red-100
                               px-4
                               py-3
                               text-sm
                               text-red-600"
                    >

                        {{ $errors->first() }}

                    </div>

                @endif



                {{-- FORM --}}

                <form
                    method="POST"
                    action="{{ route('password.store') }}"
                >

                    @csrf



                    {{-- TOKEN --}}

                    <input
                        type="hidden"
                        name="token"
                        value="{{ $token }}"
                    >



                    {{-- EMAIL --}}

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
                            value="{{ old('email', request()->email) }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="Masukkan email Anda"
                            class="w-full
                                   rounded-xl
                                   border-gray-200
                                   bg-gray-50
                                   px-4
                                   py-3
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



                    {{-- PASSWORD BARU --}}

                    <div class="mt-5">

                        <label
                            for="password"
                            class="block
                                   text-sm
                                   font-semibold
                                   text-gray-700
                                   mb-2"
                        >
                            Password Baru
                        </label>


                        <div class="relative">

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Masukkan password baru"
                                class="w-full
                                       rounded-xl
                                       border-gray-200
                                       bg-gray-50
                                       px-4
                                       py-3
                                       pr-12
                                       focus:border-[#da2424]
                                       focus:ring-[#da2424]
                                       transition
                                       duration-200"
                            >


                            {{-- TOGGLE PASSWORD --}}

                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute
                                       right-3
                                       top-1/2
                                       -translate-y-1/2
                                       text-gray-400
                                       hover:text-[#da2424]
                                       transition"
                                aria-label="Tampilkan password"
                            >

                                <svg
                                    id="eyeClosed"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />

                                </svg>


                                <svg
                                    id="eyeOpen"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 hidden"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 3l18 18"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M10.58 10.58a2 2 0 102.84 2.84"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9.88 4.24A10.94 10.94 0 0112 4c4.477 0 8.268 2.943 9.542 7a10.97 10.97 0 01-4.087 5.33"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6.61 6.61A10.98 10.98 0 002.458 12C3.732 16.057 7.523 19 12 19c1.61 0 3.13-.35 4.49-.98"
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



                    {{-- KONFIRMASI PASSWORD --}}

                    <div class="mt-5">

                        <label
                            for="password_confirmation"
                            class="block
                                   text-sm
                                   font-semibold
                                   text-gray-700
                                   mb-2"
                        >
                            Konfirmasi Password
                        </label>


                        <div class="relative">

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi password baru"
                                class="w-full
                                       rounded-xl
                                       border-gray-200
                                       bg-gray-50
                                       px-4
                                       py-3
                                       pr-12
                                       focus:border-[#da2424]
                                       focus:ring-[#da2424]
                                       transition
                                       duration-200"
                            >


                            {{-- TOGGLE KONFIRMASI --}}

                            <button
                                type="button"
                                id="togglePasswordConfirmation"
                                class="absolute
                                       right-3
                                       top-1/2
                                       -translate-y-1/2
                                       text-gray-400
                                       hover:text-[#da2424]
                                       transition"
                                aria-label="Tampilkan konfirmasi password"
                            >

                                <svg
                                    id="eyeClosedConfirmation"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />

                                </svg>


                                <svg
                                    id="eyeOpenConfirmation"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 hidden"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 3l18 18"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M10.58 10.58a2 2 0 102.84 2.84"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9.88 4.24A10.94 10.94 0 0112 4c4.477 0 8.268 2.943 9.542 7a10.97 10.97 0 01-4.087 5.33"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6.61 6.61A10.98 10.98 0 002.458 12C3.732 16.057 7.523 19 12 19c1.61 0 3.13-.35 4.49-.98"
                                    />

                                </svg>

                            </button>

                        </div>


                        @if ($errors->get('password_confirmation'))

                            <p
                                class="mt-2
                                       text-sm
                                       text-red-500"
                            >
                                {{ $errors->first('password_confirmation') }}
                            </p>

                        @endif

                    </div>



                    {{-- BUTTON --}}

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

                        Simpan Password Baru

                    </button>



                    {{-- BACK LOGIN --}}

                    <div
                        class="text-center
                               mt-6"
                    >

                        <a
                            href="{{ route('login') }}"
                            class="text-sm
                                   font-semibold
                                   text-[#da2424]
                                   hover:text-[#b91c1c]
                                   transition"
                        >

                            ← Kembali ke Login

                        </a>

                    </div>


                </form>

            </div>



            {{-- FOOTER --}}

            <p
                class="text-center
                       text-xs
                       text-gray-400
                       mt-6"
            >

                © {{ date('Y') }}
                Universitas Muhammadiyah Cirebon

            </p>

        </div>

    </div>

</div>



{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    // =====================================================
    // EMAIL LOWERCASE
    // =====================================================

    const email = document.getElementById('email');

    if (email) {

        email.addEventListener('input', function () {

            this.value = this.value.toLowerCase();

        });

    }



    // =====================================================
    // TOGGLE PASSWORD
    // =====================================================

    const togglePassword =
        document.getElementById('togglePassword');

    const password =
        document.getElementById('password');

    const eyeClosed =
        document.getElementById('eyeClosed');

    const eyeOpen =
        document.getElementById('eyeOpen');


    if (
        togglePassword &&
        password &&
        eyeClosed &&
        eyeOpen
    ) {

        togglePassword.addEventListener(
            'click',
            function () {

                if (password.type === 'password') {

                    password.type = 'text';

                    eyeClosed.classList.add('hidden');

                    eyeOpen.classList.remove('hidden');

                    togglePassword.setAttribute(
                        'aria-label',
                        'Sembunyikan password'
                    );

                } else {

                    password.type = 'password';

                    eyeOpen.classList.add('hidden');

                    eyeClosed.classList.remove('hidden');

                    togglePassword.setAttribute(
                        'aria-label',
                        'Tampilkan password'
                    );

                }

            }
        );

    }



    // =====================================================
    // TOGGLE KONFIRMASI PASSWORD
    // =====================================================

    const toggleConfirmation =
        document.getElementById(
            'togglePasswordConfirmation'
        );

    const passwordConfirmation =
        document.getElementById(
            'password_confirmation'
        );

    const eyeClosedConfirmation =
        document.getElementById(
            'eyeClosedConfirmation'
        );

    const eyeOpenConfirmation =
        document.getElementById(
            'eyeOpenConfirmation'
        );


    if (
        toggleConfirmation &&
        passwordConfirmation &&
        eyeClosedConfirmation &&
        eyeOpenConfirmation
    ) {

        toggleConfirmation.addEventListener(
            'click',
            function () {

                if (
                    passwordConfirmation.type ===
                    'password'
                ) {

                    passwordConfirmation.type =
                        'text';

                    eyeClosedConfirmation.classList
                        .add('hidden');

                    eyeOpenConfirmation.classList
                        .remove('hidden');

                    toggleConfirmation.setAttribute(
                        'aria-label',
                        'Sembunyikan konfirmasi password'
                    );

                } else {

                    passwordConfirmation.type =
                        'password';

                    eyeOpenConfirmation.classList
                        .add('hidden');

                    eyeClosedConfirmation.classList
                        .remove('hidden');

                    toggleConfirmation.setAttribute(
                        'aria-label',
                        'Tampilkan konfirmasi password'
                    );

                }

            }
        );

    }

});

</script>


</body>

</html>