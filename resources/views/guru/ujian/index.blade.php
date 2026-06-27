@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Kelola Ujian</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Buat dan kelola soal-soal ujian berteknologi Face ID.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('guru.ujian.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-700 border border-transparent rounded-lg shadow-sm hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors">
            <svg class="w-5 h-5 mr-2 -ml-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Buat Ujian Baru
        </a>
    </div>
</div>

@if(session('success'))
<div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
  <span class="font-medium">Sukses!</span> {{ session('success') }}
</div>
@endif

<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Ujian</th>
                    <th scope="col" class="px-6 py-3">Mapel</th>
                    <th scope="col" class="px-6 py-3">Kelas</th>
                    <th scope="col" class="px-6 py-3 text-center">Soal</th>
                    <th scope="col" class="px-6 py-3 text-center">Waktu Pengerjaan</th>
                    <th scope="col" class="px-6 py-3 text-center">Tipe</th>
                    <th scope="col" class="px-6 py-3 text-center">Dibuat Pada</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ujians as $ujian)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $ujian->judul }}
                    </th>
                    <td class="px-6 py-4">
                        <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">{{ $ujian->mata_pelajaran }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">{{ $ujian->id_kelas }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded mr-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500">
                            {{ $ujian->soals_count }} Soal
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center font-mono">
                        {{ $ujian->waktu_menit }} Menit
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 border border-gray-500">
                            {{ $ujian->tipe == 'essay' ? 'Essay/File' : 'Pilihan Ganda' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ $ujian->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($ujian->tipe == 'essay')
                            <a href="{{ route('guru.ujian.jawaban', $ujian->id) }}" class="font-medium text-emerald-600 dark:text-emerald-500 hover:underline mr-3">Jawaban</a>
                        @endif
                        <a href="{{ route('guru.ujian.edit', $ujian->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-3">Edit</a>
                        <form action="{{ route('guru.ujian.destroy', $ujian->id) }}" method="POST" class="inline-block form-delete" data-alert-text="Ujian beserta seluruh soalnya akan terhapus permanen!">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <p class="mt-4 text-sm">Belum ada ujian yang dibuat.</p>
                        <a href="{{ route('guru.ujian.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-500 font-medium">Buat ujian pertama &rarr;</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t dark:border-gray-700">
        {{ $ujians->links() }}
    </div>
</div>
@endsection
