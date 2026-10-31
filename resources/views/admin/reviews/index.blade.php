@extends('layouts.app')

@section('title', 'Moderasi Review UKM')
@section('page-title', 'Moderasi Review Pengguna UKM')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- HEADER & METRICS --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#1f2937]">
                Moderasi Review Pengguna UKM
            </h1>
            <p class="text-xs md:text-sm text-[#6b7280] mt-1">
                Kelola, setujui, atau tolak ulasan dan rating dari mahasiswa sebelum ditampilkan di halaman publik UKM.
            </p>
        </div>

        {{-- BADGES REKAP --}}
        <div class="flex flex-wrap items-center gap-2">
            <div class="px-4 py-2 rounded-xl bg-white border border-[#fecaca] shadow-sm flex items-center gap-2 text-xs">
                <span class="text-[#6b7280] font-medium">Total:</span>
                <span class="font-extrabold text-[#1f2937]">{{ $stats['total'] }}</span>
            </div>
            <div class="px-4 py-2 rounded-xl bg-[#fffbeb] border border-[#fef3c7] shadow-sm flex items-center gap-2 text-xs">
                <span class="text-[#b45309] font-bold">⏳ Pending:</span>
                <span class="font-extrabold text-[#b45309]">{{ $stats['pending'] }}</span>
            </div>
            <div class="px-4 py-2 rounded-xl bg-[#eaf8f2] border border-[#c9eadc] shadow-sm flex items-center gap-2 text-xs">
                <span class="text-[#258260] font-bold">✓ Approved:</span>
                <span class="font-extrabold text-[#258260]">{{ $stats['approved'] }}</span>
            </div>
            <div class="px-4 py-2 rounded-xl bg-[#fff0f1] border border-[#ffd6da] shadow-sm flex items-center gap-2 text-xs">
                <span class="text-[#d93848] font-bold">✕ Rejected:</span>
                <span class="font-extrabold text-[#d93848]">{{ $stats['rejected'] }}</span>
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-[#eaf8f2] border border-[#c9eadc] text-[#258260] rounded-2xl px-5 py-4 shadow-sm text-sm font-medium">
            <span class="font-bold text-lg">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="flex items-center gap-3 bg-[#eef2ff] border border-[#c7d2fe] text-[#3730a3] rounded-2xl px-5 py-4 shadow-sm text-sm font-medium">
            <span class="font-bold text-lg">ℹ</span>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-2xl px-5 py-4 shadow-sm text-sm font-medium">
            <span class="font-bold text-lg">✕</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- CARD TABLE & FILTER --}}
    <div class="bg-white rounded-3xl border border-[#fecaca] shadow-sm overflow-hidden">

        {{-- FILTER BAR --}}
        <div class="px-6 py-5 border-b border-[#fecaca] flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white">
            <div>
                <h2 class="text-base font-bold text-[#1f2937]">Daftar Ulasan Masuk</h2>
                <p class="text-xs text-[#6b7280] mt-0.5">Menampilkan {{ $reviews->total() }} ulasan tercatat</p>
            </div>

            <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                {{-- SEARCH --}}
                <input type="text"
                       name="q"
                       value="{{ $search }}"
                       placeholder="Cari mahasiswa, NIM, review..."
                       class="w-48 md:w-60 px-4 py-2 text-xs md:text-sm rounded-xl border border-[#fecaca] focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition bg-white">

                {{-- FILTER UKM --}}
                <select name="ukm_id" class="px-3 py-2 text-xs md:text-sm rounded-xl border border-[#fecaca] bg-white focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                    <option value="">-- Semua UKM --</option>
                    @foreach($ukmList as $u)
                        <option value="{{ $u->id }}" {{ $selectedUkm == $u->id ? 'selected' : '' }}>
                            {{ $u->nama_ukm }}
                        </option>
                    @endforeach
                </select>

                {{-- FILTER STATUS --}}
                <select name="status" class="px-3 py-2 text-xs md:text-sm rounded-xl border border-[#fecaca] bg-white focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ $selectedStatus === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="approved" {{ $selectedStatus === 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                    <option value="rejected" {{ $selectedStatus === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-[#da2424] text-white font-bold text-xs rounded-xl hover:bg-[#b91c1c] transition shadow-sm">
                    Filter
                </button>

                @if($search || $selectedUkm || $selectedStatus)
                    <a href="{{ route('admin.reviews.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-200 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-[#fdf2f2] text-[#4b5563] text-xs uppercase tracking-wider font-semibold border-b border-[#fecaca]">
                    <tr>
                        <th class="px-6 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">UKM</th>
                        <th class="px-6 py-4 text-center">Rating</th>
                        <th class="px-6 py-4">Komentar</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fecaca] text-sm text-[#1f2937]">
                    @forelse($reviews as $rev)
                        <tr class="hover:bg-[#fdf2f2]/60 transition">
                            {{-- MAHASISWA --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-[#fee2e2] text-[#da2424] flex items-center justify-center font-black text-xs flex-shrink-0">
                                        {{ strtoupper(substr($rev->mahasiswa->nama ?? 'M', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#1f2937] text-xs">
                                            {{ $rev->mahasiswa->nama ?? 'Mahasiswa' }}
                                        </div>
                                        <div class="text-[11px] text-[#6b7280]">
                                            NIM: {{ $rev->mahasiswa->nim ?? '-' }}
                                            @if(!empty($rev->mahasiswa->prodi))
                                                • {{ $rev->mahasiswa->prodi }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- UKM --}}
                            <td class="px-6 py-4 font-bold text-xs text-[#1f2937]">
                                {{ $rev->ukm->nama_ukm ?? '-' }}
                            </td>

                            {{-- RATING --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center text-amber-400 text-sm leading-none">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $rev->rating ? 'text-amber-400' : 'text-gray-300' }}">★</span>
                                    @endfor
                                </div>
                                <div class="text-[11px] font-bold text-[#6b7280] mt-0.5">
                                    {{ $rev->rating }}/5
                                </div>
                            </td>

                            {{-- KOMENTAR --}}
                            <td class="px-6 py-4 max-w-xs md:max-w-md">
                                <p class="text-xs text-[#374151] leading-relaxed whitespace-pre-line line-clamp-3">
                                    {{ $rev->komentar }}
                                </p>
                            </td>

                            {{-- TANGGAL --}}
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-[#6b7280]">
                                {{ $rev->created_at->format('d M Y') }}
                                <div class="text-[10px] text-[#9ca3af]">
                                    {{ $rev->created_at->format('H:i') }} WIB
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($rev->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#eaf8f2] text-[#258260] border border-[#c9eadc]">
                                        <span>✓</span> Approved
                                    </span>
                                @elseif($rev->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#fff0f1] text-[#d93848] border border-[#ffd6da]">
                                        <span>✕</span> Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#fffbeb] text-[#b45309] border border-[#fef3c7]">
                                        <span>⏳</span> Pending
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- APPROVE BUTTON --}}
                                    @if($rev->status !== 'approved')
                                        <form action="{{ route('admin.reviews.approve', $rev->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    title="Setujui Review (Publikasikan)"
                                                    onclick="return confirm('Apakah Anda yakin ingin menyetujui (Approve) review ini agar tampil ke publik?')"
                                                    class="px-2.5 py-1.5 rounded-lg bg-[#eaf8f2] hover:bg-[#258260] text-[#258260] hover:text-white border border-[#c9eadc] text-xs font-bold transition">
                                                ✓ Setujui
                                            </button>
                                        </form>
                                    @endif

                                    {{-- REJECT BUTTON --}}
                                    @if($rev->status !== 'rejected')
                                        <form action="{{ route('admin.reviews.reject', $rev->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    title="Tolak Review"
                                                    onclick="return confirm('Apakah Anda yakin ingin menolak (Reject) review ini?')"
                                                    class="px-2.5 py-1.5 rounded-lg bg-[#fff0f1] hover:bg-[#d93848] text-[#d93848] hover:text-white border border-[#ffd6da] text-xs font-bold transition">
                                                ✕ Tolak
                                            </button>
                                        </form>
                                    @endif

                                    {{-- DELETE BUTTON --}}
                                    <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Hapus Review Permanen"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus review ini secara permanen?')"
                                                class="px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-700 text-gray-600 hover:text-white border border-gray-200 text-xs font-bold transition">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#6b7280]">
                                <div class="text-3xl mb-2">📝</div>
                                <div class="text-sm font-bold text-[#1f2937]">Tidak Ada Review Ditemukan</div>
                                <p class="text-xs text-[#6b7280] mt-1">Belum ada review yang dikirimkan oleh mahasiswa atau filter tidak sesuai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-[#fecaca] bg-[#fdf2f2]">
                {{ $reviews->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
