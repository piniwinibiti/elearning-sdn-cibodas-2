@extends('layouts.app')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('siswa.tugas.index') }}" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Detail Tugas</h1>
</div>

@if(session('success'))
<div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
  {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
  {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Tugas Info -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $tugas->judul }}</h2>
            <div class="flex items-center gap-4 mb-6">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 border-r border-gray-300 dark:border-gray-600 pr-4">Guru: {{ $tugas->guru->user->nama_lengkap ?? 'N/A' }}</span>
                <span class="text-sm font-medium {{ now() > $tugas->deadline ? 'text-red-600' : 'text-orange-500' }}">Tenggat Waktu: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}</span>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Instruksi:</h3>
            <div class="p-4 bg-gray-50 rounded-lg dark:bg-gray-700 text-gray-700 dark:text-gray-300 whitespace-pre-line mb-6">
                {{ $tugas->instruksi }}
            </div>

            @if($tugas->file_tugas)
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Lampiran Materi:</h3>
                <div class="flex items-center p-4 mb-6 text-blue-800 border-2 border-dashed border-blue-200 rounded-xl bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-900/50">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold truncate">File Materi Pelajaran</p>
                        <p class="text-xs text-blue-600/70 dark:text-blue-400/70">Silakan unduh untuk panduan mengerjakan tugas.</p>
                    </div>
                    <a href="{{ Storage::url($tugas->file_tugas) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition-all shadow-sm">
                        Download
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Jawaban -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Pengumpulan Jawaban</h3>

            @if($jawaban)
                <div class="p-4 mb-4 bg-green-50 rounded-lg dark:bg-gray-700 border border-green-200 dark:border-green-800">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm font-semibold text-green-800 dark:text-green-400">Tugas Diserahkan</span>
                    </div>
                    <a href="{{ Storage::url($jawaban->file_jawaban) }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-500 hover:underline block truncate mb-3">
                        Lihat File Jawaban Anda
                    </a>
                    
                    @if($jawaban->nilai !== null)
                        <div class="mt-4 pt-4 border-t border-green-200 dark:border-green-800">
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Nilai:</span>
                            <span class="text-3xl font-bold {{ $jawaban->nilai >= 75 ? 'text-green-600' : 'text-orange-500' }}">{{ $jawaban->nilai }} <span class="text-sm font-normal text-gray-500">/ 100</span></span>
                        </div>
                    @else
                        <span class="inline-block mt-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Belum dinilai</span>
                    @endif
                </div>
            @endif

            @if(now() <= $tugas->deadline)
                <form action="{{ route('siswa.tugas.upload', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_jawaban">{{ $jawaban ? 'Unggah Ulang File (Opsional)' : 'Unggah File Jawaban' }}</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_jawaban" name="file_jawaban" type="file" required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Word, PDF, JPG, PNG (Maks 10MB)</p>
                    </div>
                    <button type="submit" class="w-full text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">{{ $jawaban ? 'Perbarui Jawaban' : 'Serahkan Tugas' }}</button>
                </form>
            @else
                <div class="mt-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Waktu Habis!</span> Anda tidak dapat mengirimkan jawaban lagi karena tenggat waktu sudah terlewat.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
