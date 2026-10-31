<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UkmController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\AHPController;
use App\Http\Controllers\UkmKriteriaProfileController;
use App\Http\Controllers\TopsisController;
use App\Http\Controllers\ReviewUkmController;
use App\Http\Controllers\Admin\AnalisisController;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// =====================================================
// HALAMAN UTAMA / LANDING PAGE
// =====================================================
Route::get('/', function () {

    // Admin
    if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    // Mahasiswa
    if (\Illuminate\Support\Facades\Auth::guard('mahasiswa')->check()) {
        return redirect()->route('mahasiswa.dashboard');
    }

    // Jika belum login → tampilkan Landing Page
    return view('landing');

})->name('landing');


// =====================================================
// ROUTE ADMIN
// =====================================================
Route::middleware(['auth:admin', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        // -------------------------------------------------
        // DASHBOARD ADMIN
        // -------------------------------------------------
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');


        // -------------------------------------------------
        // HALAMAN TEST ADMIN
        // -------------------------------------------------
        Route::get('/test', function () {
            return 'HALAMAN ADMIN - AKSES BERHASIL';
        })->name('admin.test');


        // -------------------------------------------------
        // CRUD DATA MAHASISWA (ADMIN)
        // -------------------------------------------------
        Route::resource('mahasiswa', \App\Http\Controllers\Admin\MahasiswaController::class)
            ->names('admin.mahasiswa')
            ->parameters([
                'mahasiswa' => 'mahasiswa',
            ]);

        Route::get('/mahasiswa/{id}/rekomendasi-pdf', [\App\Http\Controllers\Admin\MahasiswaController::class, 'downloadRekomendasiPdf'])
            ->name('admin.mahasiswa.rekomendasi.pdf');

        Route::get('/rekomendasi/rekap-pdf', [\App\Http\Controllers\Admin\MahasiswaController::class, 'downloadRekapRekomendasiPdf'])
            ->name('admin.mahasiswa.rekomendasi-rekap.pdf');


        // -------------------------------------------------
        // VERIFIKASI PENDAFTARAN UKM (ADMIN)
        // -------------------------------------------------
        Route::get('/pendaftaran-ukm', [\App\Http\Controllers\Admin\PendaftaranController::class, 'index'])
            ->name('admin.pendaftaran.index');

        Route::get('/pendaftaran-ukm/{id}', [\App\Http\Controllers\Admin\PendaftaranController::class, 'show'])
            ->name('admin.pendaftaran.show');

        Route::post('/pendaftaran-ukm/{id}/verify', [\App\Http\Controllers\Admin\PendaftaranController::class, 'verify'])
            ->name('admin.pendaftaran.verify');

        // -------------------------------------------------
        // LAPORAN REKAP PENDAFTARAN UKM (ADMIN)
        // -------------------------------------------------
        Route::get('/laporan-pendaftaran', [\App\Http\Controllers\Admin\LaporanPendaftaranController::class, 'index'])
            ->name('admin.laporan.pendaftaran.index');

        Route::get('/laporan-pendaftaran/pdf', [\App\Http\Controllers\Admin\LaporanPendaftaranController::class, 'downloadPdf'])
            ->name('admin.laporan.pendaftaran.pdf');

        // -------------------------------------------------
        // MODERASI REVIEW UKM (ADMIN)
        // -------------------------------------------------
        Route::get('/reviews', [ReviewUkmController::class, 'index'])
            ->name('admin.reviews.index');

        Route::patch('/reviews/{id}/approve', [ReviewUkmController::class, 'approve'])
            ->name('admin.reviews.approve');

        Route::patch('/reviews/{id}/reject', [ReviewUkmController::class, 'reject'])
            ->name('admin.reviews.reject');

        Route::delete('/reviews/{id}', [ReviewUkmController::class, 'adminDestroy'])
            ->name('admin.reviews.destroy');

        // -------------------------------------------------
        // ANALISIS DATA DESKRIPTIF (ADMIN)
        // -------------------------------------------------
        Route::get('/analisis', [AnalisisController::class, 'index'])
            ->name('admin.analisis.index');


        // -------------------------------------------------
        // CRUD DATA UKM
        // -------------------------------------------------
        Route::resource('ukm', UkmController::class);


        // -------------------------------------------------
        // CRUD DATA KRITERIA
        // -------------------------------------------------
        Route::resource('kriteria', KriteriaController::class)
            ->parameters([
                'kriteria' => 'kriteria',
            ]);


        // -------------------------------------------------
        // CRUD SUB KRITERIA
        // -------------------------------------------------
        Route::resource('sub-kriteria', SubKriteriaController::class)
            ->names('sub-kriteria')
            ->parameters([
                'sub-kriteria' => 'subKriteria',
            ]);


        // =================================================
        // PROFIL KRITERIA UKM
        // =================================================

        /*
         * PENTING:
         *
         * Route khusus manage dan showUkm diletakkan
         * SEBELUM Route::resource().
         *
         * Tujuannya agar:
         *
         * /{ukm}/manage
         * /{ukm}/show
         *
         * tidak dianggap sebagai parameter
         * ukmKriteriaProfile milik route CRUD.
         */


        // -------------------------------------------------
        // MANAGE PROFIL SATU UKM
        // -------------------------------------------------
        Route::get(
            '/ukm-kriteria-profile/{ukm}/manage',
            [UkmKriteriaProfileController::class, 'manage']
        )
            ->name('ukm-kriteria-profile.manage');


        // -------------------------------------------------
        // SIMPAN PROFIL SATU UKM
        // -------------------------------------------------
        Route::post(
            '/ukm-kriteria-profile/{ukm}/manage',
            [UkmKriteriaProfileController::class, 'saveManage']
        )
            ->name('ukm-kriteria-profile.manage.save');


        // -------------------------------------------------
        // DETAIL PROFIL SATU UKM
        // -------------------------------------------------
        Route::get(
            '/ukm-kriteria-profile/{ukm}/show',
            [UkmKriteriaProfileController::class, 'showUkm']
        )
            ->name('ukm-kriteria-profile.show-ukm');


        // -------------------------------------------------
        // CRUD PROFIL KRITERIA UKM
        // -------------------------------------------------
        Route::resource(
            'ukm-kriteria-profile',
            UkmKriteriaProfileController::class
        )
            ->names('ukm-kriteria-profile')
            ->parameters([
                'ukm-kriteria-profile' => 'ukmKriteriaProfile',
            ]);


        // -------------------------------------------------
        // PERHITUNGAN AHP
        // -------------------------------------------------
        Route::get('/ahp', [AHPController::class, 'index'])
            ->name('ahp.index');

        Route::post('/ahp', [AHPController::class, 'store'])
            ->name('ahp.store');


        // =================================================
        // PERHITUNGAN TOPSIS
        // =================================================

        // Halaman utama TOPSIS
        Route::get('/topsis', [TopsisController::class, 'index'])
            ->name('topsis.index');

        // Proses perhitungan TOPSIS
        Route::post('/topsis/calculate', [TopsisController::class, 'calculate'])
            ->name('topsis.calculate');

        // Menampilkan hasil TOPSIS berdasarkan ID proses
        Route::get('/topsis/{id}', [TopsisController::class, 'show'])
            ->name('topsis.show');

    });


// =====================================================
// ROUTE MAHASISWA
// =====================================================
Route::middleware(['auth:mahasiswa', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->group(function () {

        // -------------------------------------------------
        // DASHBOARD MAHASISWA (Dapat diakses walau profil belum lengkap)
        // -------------------------------------------------
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('mahasiswa.dashboard');

        // -------------------------------------------------
        // DATA UKM MAHASISWA (READ-ONLY)
        // -------------------------------------------------
        Route::get('/ukm', [\App\Http\Controllers\Mahasiswa\UkmController::class, 'index'])
            ->name('mahasiswa.ukm.index');

        Route::get('/ukm/{ukm}', [\App\Http\Controllers\Mahasiswa\UkmController::class, 'show'])
            ->name('mahasiswa.ukm.show');

        // -------------------------------------------------
        // REVIEW UKM (MAHASISWA)
        // -------------------------------------------------
        Route::post('/reviews', [ReviewUkmController::class, 'store'])
            ->name('mahasiswa.reviews.store');

        Route::put('/reviews/{id}', [ReviewUkmController::class, 'update'])
            ->name('mahasiswa.reviews.update');

        Route::delete('/reviews/{id}', [ReviewUkmController::class, 'destroy'])
            ->name('mahasiswa.reviews.destroy');

        // -------------------------------------------------
        // ROUTE MAHASISWA YANG WAJIB PROFIL LENGKAP
        // -------------------------------------------------
        Route::middleware(['profile.complete'])->group(function () {

            // KUESIONER PREFERENSI MAHASISWA
            Route::get('/kuesioner', [\App\Http\Controllers\Mahasiswa\KuesionerController::class, 'index'])
                ->name('mahasiswa.kuesioner.index');

            Route::post('/kuesioner', [\App\Http\Controllers\Mahasiswa\KuesionerController::class, 'store'])
                ->name('mahasiswa.kuesioner.store');

            // HASIL REKOMENDASI TOPSIS DINAMIS
            Route::get('/rekomendasi', [\App\Http\Controllers\Mahasiswa\RekomendasiController::class, 'index'])
                ->name('mahasiswa.rekomendasi.index');

            Route::get('/rekomendasi/{id}/pdf', [\App\Http\Controllers\Mahasiswa\RekomendasiController::class, 'downloadPdf'])
                ->name('mahasiswa.rekomendasi.pdf');

            // PENDAFTARAN UKM ONLINE
            Route::get('/pendaftaran', [\App\Http\Controllers\Mahasiswa\PendaftaranController::class, 'index'])
                ->name('mahasiswa.pendaftaran.index');

            Route::get('/pendaftaran/create/{ukm}', [\App\Http\Controllers\Mahasiswa\PendaftaranController::class, 'create'])
                ->name('mahasiswa.pendaftaran.create');

            Route::post('/pendaftaran', [\App\Http\Controllers\Mahasiswa\PendaftaranController::class, 'store'])
                ->name('mahasiswa.pendaftaran.store');

            Route::get('/pendaftaran/{id}/edit', [\App\Http\Controllers\Mahasiswa\PendaftaranController::class, 'edit'])
                ->name('mahasiswa.pendaftaran.edit');

            Route::put('/pendaftaran/{id}', [\App\Http\Controllers\Mahasiswa\PendaftaranController::class, 'update'])
                ->name('mahasiswa.pendaftaran.update');

            Route::get('/pendaftaran/{id}/pdf', [\App\Http\Controllers\Mahasiswa\PendaftaranController::class, 'downloadPdf'])
                ->name('mahasiswa.pendaftaran.pdf');

        });

        // -------------------------------------------------
        // HALAMAN TEST MAHASISWA
        // -------------------------------------------------
        Route::get('/test', function () {
            return 'HALAMAN MAHASISWA - AKSES BERHASIL';
        })->name('mahasiswa.test');

    });


// =====================================================
// PROFILE
// =====================================================
Route::middleware('auth:admin,mahasiswa')
    ->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');

    });


// =====================================================
// AUTHENTICATION
// =====================================================
require __DIR__ . '/auth.php';