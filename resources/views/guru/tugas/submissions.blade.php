@extends('layouts.app')

@section('content')
<div class="mb-4 flex items-center gap-3">
    <a href="{{ route('guru.tugas.index') }}" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Penilaian: {{ $tugas->judul }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelas {{ $tugas->id_kelas }} | Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y H:i') }}</p>
    </div>
</div>

@if(session('success'))
<div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
  {{ session('success') }}
</div>
@endif

<div class="relative overflow-x-auto shadow-sm sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Nama Siswa</th>
                <th scope="col" class="px-6 py-3">Waktu Pengumpulan</th>
                <th scope="col" class="px-6 py-3">File Jawaban</th>
                <th scope="col" class="px-6 py-3">Nilai</th>
                <th scope="col" class="px-6 py-3 text-right">Aksi Penilaian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jawabans as $jawaban)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $jawaban->siswa->user->nama_lengkap }}
                </th>
                <td class="px-6 py-4 {{ $jawaban->updated_at > $tugas->deadline ? 'text-red-500' : '' }}">
                    {{ $jawaban->updated_at->format('d M Y H:i') }}
                    @if($jawaban->updated_at > $tugas->deadline)
                        <span class="block text-xs uppercase text-red-600">Terlambat</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <a href="{{ Storage::url($jawaban->file_jawaban) }}" target="_blank" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Download/Lihat</a>
                </td>
                <td class="px-6 py-4 font-bold {{ $jawaban->nilai >= 75 ? 'text-green-500' : ($jawaban->nilai !== null ? 'text-orange-500' : 'text-gray-400') }}">
                    {{ $jawaban->nilai ?? 'Belum Dinilai' }}
                </td>
                <td class="px-6 py-4 text-right">
                    <form action="{{ route('guru.tugas.grade', $jawaban->id) }}" method="POST" class="flex justify-end items-center gap-2">
                        @csrf
                        <input type="number" name="nilai" min="0" max="100" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-20 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $jawaban->nilai }}" placeholder="0-100" required>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Simpan</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada siswa yang mengumpulkan tugas ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
