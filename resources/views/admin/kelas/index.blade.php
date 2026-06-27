@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Kelola Data Kelas</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pusat kelola master data kelas untuk opsi guru dan siswa</p>
    </div>
    <div class="mt-4 md:mt-0 flex space-x-2">
        <button type="button" data-modal-target="addKelasModal" data-modal-toggle="addKelasModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition">
            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kelas Baru
        </button>
    </div>
</div>

@if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800 alert-dismissible fade show" role="alert">
        <span class="font-medium">Berhasil!</span> {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800 alert-dismissible fade show" role="alert">
        <ul class="list-disc pl-5 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-4">No.</th>
                    <th scope="col" class="px-6 py-4">Nama Kelas</th>
                    <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelasList as $index => $kelas)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <th scope="row" class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap dark:text-white">
                        <span class="bg-blue-100 text-blue-800 text-sm font-bold px-3 py-1 rounded dark:bg-blue-900 dark:text-blue-300">
                            {{ $kelas->nama_kelas }}
                        </span>
                    </th>
                    <td class="px-6 py-4 text-right flex justify-end space-x-2">
                        <button type="button" data-modal-target="editKelasModal-{{ $kelas->id }}" data-modal-toggle="editKelasModal-{{ $kelas->id }}" class="text-amber-500 hover:text-white border border-amber-500 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center dark:border-amber-400 dark:text-amber-400 dark:hover:text-white dark:hover:bg-amber-500 dark:focus:ring-amber-900 transition">
                            Edit
                        </button>
                        <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" class="inline-block form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-white border border-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Kelas Modal for each -->
                <div id="editKelasModal-{{ $kelas->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST" class="relative bg-white rounded-xl shadow-xl dark:bg-gray-800">
                            @csrf
                            @method('PUT')
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Edit Kelas
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="editKelasModal-{{ $kelas->id }}">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <div class="p-4 md:p-5 space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kelas</label>
                                    <input type="text" name="nama_kelas" value="{{ $kelas->nama_kelas }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                </div>
                            </div>
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-700">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Simpan Perubahan</button>
                                <button data-modal-toggle="editKelasModal-{{ $kelas->id }}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        Belum ada data kelas yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Kelas Modal -->
<div id="addKelasModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <form action="{{ route('admin.kelas.store') }}" method="POST" class="relative bg-white rounded-xl shadow-xl dark:bg-gray-800">
            @csrf
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Tambah Kelas Baru
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="addKelasModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4 md:p-5 space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kelas</label>
                    <input type="text" name="nama_kelas" placeholder="Misal: 1A" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                </div>
            </div>
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-700">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambahkan Kelas</button>
                <button data-modal-toggle="addKelasModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection
