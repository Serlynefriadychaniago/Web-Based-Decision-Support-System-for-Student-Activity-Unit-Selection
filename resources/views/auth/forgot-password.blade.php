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
        Lupa Password - SPK Pemilihan UKM UMC
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
                    Lupa Password?
                </h2>


                <p
                    class="mt-2
                           text-sm
                           text-gray-500"
                >
                    Masukkan email yang terdaftar
                    untuk membuat password baru.
                </p>

            </div>



            {{-- CARD --}}

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
                    action="{{ route('password.email') }}"
                >

                    @csrf



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
                            value="{{ old('email') }}"
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

                        Buat Password Baru

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



<script>

document.addEventListener('DOMContentLoaded', function () {

    const email = document.getElementById('email');

    if (email) {

        email.addEventListener('input', function () {

            this.value = this.value.toLowerCase();

        });

    }

});

</script>


</body>

</html>