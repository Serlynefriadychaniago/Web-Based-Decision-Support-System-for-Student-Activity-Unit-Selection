<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- FAVICON --}}
    <link rel="icon" type="image/png" href="{{ asset('images/login/logo.png') }}">

    <title>Daftar - SPK Pemilihan UKM UMC</title>

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
             ===================================================== --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">

            {{-- Background Kampus --}}
            <img
                src="{{ asset('images/login/bg.png') }}"
                alt="Universitas Muhammadiyah Cirebon"
                class="absolute inset-0 w-full h-full object-cover"
            >

            {{-- Overlay Ungu --}}
            <div class="absolute inset-0 bg-gradient-to-br
                        from-[#991b1b]/90
                        via-[#b91c1c]/75
                        to-[#da2424]/70">
            </div>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col justify-between w-full p-12 text-white">

                {{-- =================================================
                     LOGO
                     ================================================= --}}
                <div>

                    <img
                        src="{{ asset('images/login/logo.png') }}"
                        alt="Logo Universitas Muhammadiyah Cirebon"
                        class="w-24 h-24 object-contain drop-shadow-lg"
                    >

                </div>


                {{-- =================================================
                     TEXT
                     ================================================= --}}
                <div class="max-w-xl mb-10">

                    <p class="text-sm uppercase tracking-[0.25em]
                              font-semibold text-white/80 mb-4">

                        Sistem Pendukung Keputusan

                    </p>


                    <h1 class="text-4xl xl:text-5xl
                               font-extrabold
                               leading-tight
                               mb-5">

                        Pemilihan UKM
                        <br>
                        Sesuai Minat Mahasiswa

                    </h1>


                    <p class="text-white/85
                              text-base
                              leading-relaxed
                              max-w-lg">

                        Temukan Unit Kegiatan Mahasiswa yang paling sesuai
                        dengan minat dan bakatmu melalui sistem rekomendasi
                        berbasis AHP dan TOPSIS.

                    </p>

                </div>


                {{-- =================================================
                     FOOTER
                     ================================================= --}}
                <div class="text-sm text-white/70">

                    © {{ date('Y') }} Universitas Muhammadiyah Cirebon

                </div>

            </div>

        </div>


        {{-- =====================================================
             BAGIAN KANAN
             ===================================================== --}}
        <div class="w-full lg:w-1/2
                    flex items-center justify-center
                    px-6 py-10">

            <div class="w-full max-w-md">


                {{-- =================================================
                     LOGO MOBILE
                     ================================================= --}}
                <div class="flex justify-center lg:hidden mb-6">

                    <img
                        src="{{ asset('images/login/logo.png') }}"
                        alt="Logo Universitas Muhammadiyah Cirebon"
                        class="w-20 h-20 object-contain"
                    >

                </div>


                {{-- =================================================
                     HEADING
                     ================================================= --}}
                <div class="text-center mb-8">

                    <h2 class="text-3xl font-extrabold text-gray-800">

                        Buat Akun

                    </h2>

                    <p class="mt-2 text-sm text-gray-500">

                        Daftarkan dirimu untuk mendapatkan rekomendasi UKM

                    </p>

                </div>


                {{-- =================================================
                     CARD FORM
                     ================================================= --}}
                <div class="bg-white
                            rounded-3xl
                            shadow-xl
                            shadow-red-100/60
                            p-7 sm:p-8">

                    <form
                        method="POST"
                        action="{{ route('register') }}"
                        autocomplete="on"
                    >

                        @csrf


                        {{-- =================================================
                             NAMA LENGKAP
                             ================================================= --}}
                        <div>

                            <label
                                for="name"
                                class="block text-sm
                                       font-semibold
                                       text-gray-700
                                       mb-2"
                            >
                                Nama Lengkap
                            </label>


                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Masukkan nama lengkap"
                                class="w-full
                                       rounded-xl
                                       border-gray-200
                                       bg-gray-50
                                       focus:border-[#da2424]
                                       focus:ring-[#da2424]
                                       transition duration-200"
                            >


                            @if ($errors->get('name'))

                                <p class="mt-2 text-sm text-red-500">

                                    {{ $errors->first('name') }}

                                </p>

                            @endif

                        </div>


                        {{-- =================================================
                             USERNAME
                             ================================================= --}}
                        <div class="mt-5">

                            <label
                                for="username"
                                class="block text-sm
                                       font-semibold
                                       text-gray-700
                                       mb-2"
                            >
                                Username
                            </label>


                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autocomplete="username"
                                placeholder="Masukkan username"
                                class="w-full
                                       rounded-xl
                                       border-gray-200
                                       bg-gray-50
                                       focus:border-[#da2424]
                                       focus:ring-[#da2424]
                                       transition duration-200"
                            >


                            @if ($errors->get('username'))

                                <p class="mt-2 text-sm text-red-500">

                                    {{ $errors->first('username') }}

                                </p>

                            @endif

                        </div>


                        {{-- =================================================
                             EMAIL
                             ================================================= --}}
                        <div class="mt-5">

                            <label
                                for="email"
                                class="block text-sm
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
                                autocomplete="email"
                                autocapitalize="none"
                                spellcheck="false"
                                placeholder="contoh@email.com"
                                class="w-full
                                       rounded-xl
                                       border-gray-200
                                       bg-gray-50
                                       focus:border-[#da2424]
                                       focus:ring-[#da2424]
                                       transition duration-200"
                            >


                            @if ($errors->get('email'))

                                <p class="mt-2 text-sm text-red-500">

                                    {{ $errors->first('email') }}

                                </p>

                            @endif

                        </div>


                        {{-- =================================================
                             PASSWORD
                             ================================================= --}}
                        <div class="mt-5">

                            <label
                                for="password"
                                class="block text-sm
                                       font-semibold
                                       text-gray-700
                                       mb-2"
                            >
                                Password
                            </label>


                            {{-- Password Wrapper --}}
                            <div class="relative">

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Masukkan password"
                                    class="w-full
                                           rounded-xl
                                           border-gray-200
                                           bg-gray-50
                                           pr-12
                                           focus:border-[#da2424]
                                           focus:ring-[#da2424]
                                           transition duration-200"
                                >


                                {{-- Toggle Password --}}
                                <button
                                    type="button"
                                    onclick="togglePassword(
                                        'password',
                                        'password-eye',
                                        'password-eye-off'
                                    )"
                                    class="absolute
                                           right-3
                                           top-1/2
                                           -translate-y-1/2
                                           text-gray-400
                                           hover:text-[#da2424]
                                           transition"
                                    aria-label="Tampilkan password"
                                >

                                    {{-- Eye --}}
                                    <svg
                                        id="password-eye"
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


                                    {{-- Eye Off --}}
                                    <svg
                                        id="password-eye-off"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="w-5 h-5 hidden"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.98 8.223A10.477 10.477 0 002.036
                                               12.322a1.012 1.012 0 000 .644
                                               C3.423 17.49 7.36 20 12 20
                                               c1.61 0 3.13-.36 4.49-1.005"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6.228 6.228A10.451 10.451 0 0112 4
                                               c4.64 0 8.577 2.51 9.964 6.678
                                               a1.012 1.012 0 010 .644
                                               10.49 10.49 0 01-4.13 5.097"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6.228 6.228L3 3m3.228 3.228
                                               l3.182 3.182m5.36 5.36L21 21"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9.88 9.88a3 3 0 104.24 4.24"
                                        />

                                    </svg>

                                </button>

                            </div>


                            @if ($errors->get('password'))

                                <p class="mt-2 text-sm text-red-500">

                                    {{ $errors->first('password') }}

                                </p>

                            @endif

                        </div>


                        {{-- =================================================
                             KONFIRMASI PASSWORD
                             ================================================= --}}
                        <div class="mt-5">

                            <label
                                for="password_confirmation"
                                class="block text-sm
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
                                    placeholder="Ulangi password"
                                    class="w-full
                                           rounded-xl
                                           border-gray-200
                                           bg-gray-50
                                           pr-12
                                           focus:border-[#da2424]
                                           focus:ring-[#da2424]
                                           transition duration-200"
                                >


                                {{-- Toggle Konfirmasi Password --}}
                                <button
                                    type="button"
                                    onclick="togglePassword(
                                        'password_confirmation',
                                        'confirmation-eye',
                                        'confirmation-eye-off'
                                    )"
                                    class="absolute
                                           right-3
                                           top-1/2
                                           -translate-y-1/2
                                           text-gray-400
                                           hover:text-[#da2424]
                                           transition"
                                    aria-label="Tampilkan konfirmasi password"
                                >

                                    {{-- Eye --}}
                                    <svg
                                        id="confirmation-eye"
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


                                    {{-- Eye Off --}}
                                    <svg
                                        id="confirmation-eye-off"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="w-5 h-5 hidden"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.98 8.223A10.477 10.477 0 002.036
                                               12.322a1.012 1.012 0 000 .644
                                               C3.423 17.49 7.36 20 12 20
                                               c1.61 0 3.13-.36 4.49-1.005"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6.228 6.228A10.451 10.451 0 0112 4
                                               c4.64 0 8.577 2.51 9.964 6.678
                                               a1.012 1.012 0 010 .644
                                               10.49 10.49 0 01-4.13 5.097"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6.228 6.228L3 3m3.228 3.228
                                               l3.182 3.182m5.36 5.36L21 21"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9.88 9.88a3 3 0 104.24 4.24"
                                        />

                                    </svg>

                                </button>

                            </div>


                            @if ($errors->get('password_confirmation'))

                                <p class="mt-2 text-sm text-red-500">

                                    {{ $errors->first('password_confirmation') }}

                                </p>

                            @endif

                        </div>


                        {{-- =================================================
                             BUTTON DAFTAR
                             ================================================= --}}
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

                            Daftar

                        </button>


                        {{-- =================================================
                             LOGIN
                             ================================================= --}}
                        <div class="text-center mt-6">

                            <span class="text-sm text-gray-500">

                                Sudah memiliki akun?

                            </span>


                            <a
                                href="{{ route('login') }}"
                                class="text-sm
                                       font-semibold
                                       text-[#da2424]
                                       hover:text-[#b91c1c]
                                       transition"
                            >

                                Masuk sekarang

                            </a>

                        </div>

                    </form>

                </div>


                {{-- =================================================
                     FOOTER MOBILE
                     ================================================= --}}
                <p class="text-center text-xs text-gray-400 mt-6">

                    © {{ date('Y') }} Universitas Muhammadiyah Cirebon

                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
         JAVASCRIPT
         ========================================================= --}}
    <script>

        /*
        |--------------------------------------------------------------------------
        | Toggle Password
        |--------------------------------------------------------------------------
        */

        function togglePassword(inputId, eyeId, eyeOffId) {

            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);

            if (!input) {
                return;
            }

            if (input.type === 'password') {

                input.type = 'text';

                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');

            } else {

                input.type = 'password';

                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Email Otomatis Lowercase
        |--------------------------------------------------------------------------
        */

        document.addEventListener('DOMContentLoaded', function () {

            const emailInput = document.getElementById('email');

            if (!emailInput) {
                return;
            }

            emailInput.addEventListener('input', function () {

                const start = this.selectionStart;
                const end = this.selectionEnd;

                this.value = this.value.toLowerCase();

                this.setSelectionRange(start, end);

            });

        });

    </script>

</body>

</html>