@extends('layouts.app')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Rekapitulasi Laporan</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data kehadiran (Face Recognition) dan Nilai Rata-rata Tugas siswa.</p>
    </div>
    <a href="{{ route('guru.laporan.pdf') }}" class="inline-flex items-center text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Cetak PDF
    </a>
</div>

<div class="relative overflow-x-auto shadow-sm sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Nama Siswa / NIS</th>
                <th scope="col" class="px-6 py-3">Kelas</th>
                <th scope="col" class="px-6 py-3 text-center">Total Hadir</th>
                <th scope="col" class="px-6 py-3 text-center">Tugas Selesai</th>
                <th scope="col" class="px-6 py-3 text-center">Rata-rata Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanData as $data)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $data['siswa']->user->nama_lengkap }} <br>
                    <span class="text-xs text-gray-500">{{ $data['siswa']->nis }}</span>
                </th>
                <td class="px-6 py-4">{{ $data['siswa']->id_kelas }}</td>
                <td class="px-6 py-4 text-center">
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $data['hadir'] }} Hari</span>
                </td>
                <td class="px-6 py-4 text-center">
                    {{ $data['tugas_terkumpul'] }} / {{ $data['total_tugas'] }}
                </td>
                <td class="px-6 py-4 text-center font-bold text-lg {{ $data['rata_rata_tugas'] >= 75 ? 'text-green-500' : 'text-orange-500' }}">
                    {{ $data['rata_rata_tugas'] }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Data siswa tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
