@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between pointer-events-none">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Jawaban Ujian</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lembar jawaban essay/tugas yang dikumpulkan siswa.</p>
    </div>
    <a href="{{ route('guru.ujian.index') }}" class="pointer-events-auto text-sm font-medium text-blue-600 hover:underline dark:text-blue-500 flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali
    </a>
</div>

<!-- Informasi Ujian -->
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Judul Ujian</h3>
            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $ujian->judul }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kelas</h3>
            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $ujian->id_kelas }}</p>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Batas Waktu</h3>
            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $ujian->waktu_menit }} Menit</p>
        </div>
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pengumpulan</h3>
            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $jawabans->count() }} Terkumpul</p>
        </div>
    </div>
    
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Instruksi Soal:</h3>
        <p class="text-gray-900 dark:text-gray-300 whitespace-pre-wrap bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">{{ $ujian->teks_essay }}</p>
    </div>
</div>

@if(session('success'))
<div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
    <span class="font-medium">Success!</span> {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
    <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Tabel Jawaban -->
<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">NISN</th>
                    <th scope="col" class="px-6 py-3">Nama Siswa</th>
                    <th scope="col" class="px-6 py-3 text-center">Waktu Submit</th>
                    <th scope="col" class="px-6 py-3 text-center">File Jawaban</th>
                    <th scope="col" class="px-6 py-3 text-center">Status / Nilai</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi Penilaian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jawabans as $jawaban)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $jawaban->siswa->nisn }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        {{ $jawaban->siswa->nama }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ $jawaban->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ Storage::url('jawaban_essay/' . $jawaban->file_path) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Lihat File
                        </a>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if(is_null($jawaban->nilai))
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Belum Dinilai</span>
                        @else
                            <span class="bg-emerald-100 text-emerald-800 text-sm font-bold px-3 py-1 rounded dark:bg-emerald-900 dark:text-emerald-300">{{ $jawaban->nilai }} / 100</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('guru.ujian.nilai', $jawaban->id) }}" method="POST" class="flex items-center justify-end space-x-2">
                            @csrf
                            <input type="number" name="nilai" min="0" max="100" value="{{ $jawaban->nilai }}" placeholder="Nilai (0-100)" class="w-24 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Simpan
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p class="text-lg font-medium">Belum ada jawaban yang dikumpulkan</p>
                            <p class="text-sm mt-1">Siswa kelas {{ $ujian->id_kelas }} belum mengunggah file essay mereka.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
