@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Kelola Data Siswa</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tambah dan lihat daftar siswa.</p>
</div>

<div class="flex flex-col sm:flex-row items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-0">Daftar Siswa</h2>
    <button data-modal-target="crud-modal-siswa" data-modal-toggle="crud-modal-siswa" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex items-center" type="button">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
        Tambah Siswa Baru
    </button>
</div>

<!-- Main modal -->
<div id="crud-modal-siswa" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-4xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Tambah Siswa Baru & Biometrik
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal-siswa">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="{{ route('admin.siswa.store') }}" method="POST" class="p-4 md:p-5" id="form-tambah-siswa">
                @csrf
                <div id="face-samples-container"></div>

                @php
                    $faceErrors = collect($errors->keys())
                        ->filter(fn ($k) => str_starts_with($k, 'face_samples'))
                        ->flatMap(fn ($k) => $errors->get($k))
                        ->unique()
                        ->values()
                        ->all();
                @endphp
                <x-input-error :messages="$faceErrors" />

                <div class="grid gap-6 mb-4 grid-cols-1 md:grid-cols-2">
                    <!-- Kolom Kiri: Form Data Siswa -->
                    <div class="space-y-4">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white border-b pb-2 dark:border-gray-600">Informasi Siswa</h4>
                        
                        <div>
                            <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap Siswa</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                                    </svg>
                                </div>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                            </div>
                            <x-input-error name="nama_lengkap" />
                        </div>

                        <div>
                            <label for="nis" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIS (Username)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 2a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1M2 5h12v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm0 0V4a2 2 0 0 1 2-2h3m0 0v2m0 0h2m-2-2h-2m-2 0H2a2 2 0 0 0-2 2v1"/>
                                    </svg>
                                </div>
                                <input type="text" id="nis" name="nis" value="{{ old('nis') }}" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" title="NIS harus tepat 10 digit angka" required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Harus tepat 10 digit angka, tanpa huruf atau spasi. NIS otomatis digunakan sebagai username.</p>
                            <x-input-error name="nis" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="id_kelas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                                <select id="id_kelas" name="id_kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                                    @foreach(\App\Models\Kelas::orderBy('nama_kelas')->pluck('nama_kelas') as $kelas)
                                        <option value="{{ $kelas }}" {{ old('id_kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                    @endforeach
                                </select>
                                <x-input-error name="id_kelas" />
                            </div>

                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sandi Awal</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required minlength="6">
                                    <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition-colors" data-target="password">
                                        <svg class="eye-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg class="eye-off-icon w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                    </button>
                                </div>
                                <x-input-error name="password" />
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Web Camera Biometrik -->
                    <div class="space-y-4">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white border-b pb-2 dark:border-gray-600">Pindai Wajah (Opsional/Sangat Disarankan)</h4>
                        
                        <div class="relative w-full aspect-square md:aspect-video bg-gray-900 rounded-lg overflow-hidden flex items-center justify-center shadow-inner border border-gray-300 dark:border-gray-600">
                            <!-- Video feed -->
                            <video id="siswa-webcam" autoplay playsinline class="absolute top-0 left-0 w-full h-full object-cover transform scale-x-[-1] hidden"></video>
                            
                            <!-- Overlay kotak wajah -->
                            <div id="webcam-overlay" class="absolute inset-x-0 inset-y-0 z-10 pointer-events-none flex items-center justify-center border-2 border-dashed border-white/50 rounded-xl px-12 py-10 m-6 hidden"></div>
                            
                            <!-- Loading / Starter ui -->
                            <div id="webcam-starter" class="z-20 text-white flex flex-col items-center cursor-pointer hover:text-blue-400 group p-4 text-center transition-colors">
                                <svg class="w-12 h-12 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                <span class="text-sm font-semibold">Klik untuk Aktifkan Kamera</span>
                                <span class="text-xs text-gray-400 mt-1">Arahkan wajah siswa ke kamera untuk pendaftaran.</span>
                            </div>
                        </div>

                        <!-- Progress / Status -->
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Sampel Wajah Tersimpan (<span id="sample-count-text">0</span>/30)</span>
                                <span id="camera-status-text" class="text-xs font-medium text-gray-500">Kamera Offline</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 overflow-hidden">
                                <div id="sample-progress-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <button type="button" id="btn-start-record" disabled class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Mulai Ambil Sampel Wajah
                        </button>
                    </div>
                </div>

                <div class="mt-6 border-t pt-4 border-gray-200 dark:border-gray-600">
                    <button type="submit" id="btn-submit-siswa" class="text-white inline-flex items-center w-full justify-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors">
                        <svg class="me-1 -ms-1 w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Simpan Data & Biometrik Siswa
                    </button>
                    <p class="text-xs text-center text-gray-500 mt-2">Data wajah yang diambil akan digunakan untuk face recognition absen & login.</p>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Control Bar (Filter & Search & Bulk Action) -->
<div class="flex flex-col mb-4 space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
    <div class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0 text-sm">
        <form id="bulk-delete-form" action="{{ route('admin.siswa.bulk_destroy') }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" id="bulk-delete-btn" class="hidden text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center items-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus Terpilih (<span id="selected-count">0</span>)
            </button>
        </form>

        <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0">
            <!-- Filter Kelas -->
            <select name="kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasOptions as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                @endforeach
            </select>
            
            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" id="search" class="block w-full md:w-64 p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Siswa atau NIS...">
            </div>
            @if(request('search') || request('kelas'))
                <a href="{{ route('admin.siswa.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-4 py-2 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 text-center">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Tabel Daftar Siswa -->
    <div class="relative overflow-x-auto shadow-sm sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4">
                        <div class="flex items-center">
                            <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-all" class="sr-only">checkbox</label>
                        </div>
                    </th>
                <th scope="col" class="px-6 py-3">Nama Siswa</th>
                <th scope="col" class="px-6 py-3">NIS (Username)</th>
                <th scope="col" class="px-6 py-3">Kelas</th>
                <th scope="col" class="px-6 py-3">Tanggal Didaftarkan</th>
                <th scope="col" class="px-6 py-3 border-s dark:border-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswas as $siswa)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="w-4 p-4">
                    <div class="flex items-center">
                        <input form="bulk-delete-form" id="checkbox-table-{{ $siswa->id }}" type="checkbox" name="ids[]" value="{{ $siswa->id }}" class="item-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-table-{{ $siswa->id }}" class="sr-only">checkbox</label>
                    </div>
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $siswa->user->nama_lengkap }}
                </th>
                <td class="px-6 py-4 font-mono">{{ $siswa->nis }}</td>
                <td class="px-6 py-4">
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $siswa->id_kelas }}</span>
                </td>
                <td class="px-6 py-4">{{ $siswa->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4 border-s dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <!-- Tombol Edit -->
                        <button data-modal-target="edit-modal-siswa-{{ $siswa->id }}" data-modal-toggle="edit-modal-siswa-{{ $siswa->id }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline flex items-center" type="button">
                            <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m13.835 7.578-.005.007-7.137 7.137 2.139 2.138 7.143-7.142-2.14-2.14Zm-10.696 3.59 2.139 2.14 7.138-7.137.002-.002-2.136-2.138-7.143 7.137ZM15.969 5.44 14.56 4.032a1.5 1.5 0 0 0-2.122 0l-.888.888 2.138 2.138.888-.888a1.5 1.5 0 0 0 0-2.122Z"/></svg>
                            Edit
                        </button>
                        
                        <!-- Form Hapus -->
                        <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" class="inline-block form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline flex items-center">
                                <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h16M7 8v8m4-8v8M7 1h4a1 1 0 0 1 1 1v3H6V2a1 1 0 0 1 1-1ZM3 5h12v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5Z"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>

                    <!-- Modal Edit Siswa -->
                    <div id="edit-modal-siswa-{{ $siswa->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-4xl max-h-full">
                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Siswa</h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="edit-modal-siswa-{{ $siswa->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                        <span class="sr-only">Tutup modal</span>
                                    </button>
                                </div>
                                <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" class="p-4" id="form-edit-siswa-{{ $siswa->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div id="edit-face-samples-container-{{ $siswa->id }}"></div>
                                    @php
                                        $editFaceErrors = collect($errors->keys())
                                            ->filter(fn ($k) => str_starts_with($k, 'face_samples'))
                                            ->flatMap(fn ($k) => $errors->get($k))
                                            ->unique()
                                            ->values()
                                            ->all();
                                    @endphp
                                    <x-input-error :messages="$editFaceErrors" />
                                    <div class="grid gap-6 mb-4 grid-cols-1 md:grid-cols-2">
                                        <div class="space-y-4">
                                            <h4 class="text-md font-semibold text-gray-900 dark:text-white border-b pb-2 dark:border-gray-600">Informasi Siswa</h4>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $siswa->user->nama_lengkap) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                            <x-input-error name="nama_lengkap" />
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIS / Username</label>
                                            <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" title="NIS harus tepat 10 digit angka" required>
                                            <p class="text-xs text-gray-500 mt-1">Harus tepat 15 digit angka. Nilai ini juga menjadi username login siswa.</p>
                                            <x-input-error name="nis" />
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                                            <select name="id_kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                                @foreach(\App\Models\Kelas::orderBy('nama_kelas')->pluck('nama_kelas') as $kelas)
                                                    <option value="{{ $kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error name="id_kelas" />
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sandi Baru (Kosongkan jika tetap)</label>
                                            <div class="relative">
                                                <input type="password" id="edit-password-{{ $siswa->id }}" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" minlength="6">
                                                <button type="button" class="password-toggle absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition-colors" data-target="edit-password-{{ $siswa->id }}">
                                                    <svg class="eye-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    <svg class="eye-off-icon w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                                                </button>
                                            </div>
                                            <x-input-error name="password" />
                                        </div>
                                        </div>

                                        <!-- Kolom Kanan: Web Camera Biometrik -->
                                        <div class="space-y-4">
                                            <h4 class="text-md font-semibold text-gray-900 dark:text-white border-b pb-2 dark:border-gray-600">Retake Wajah (Opsional)</h4>
                                            
                                            <div class="relative w-full aspect-square md:aspect-video bg-gray-900 rounded-lg overflow-hidden flex items-center justify-center shadow-inner border border-gray-300 dark:border-gray-600">
                                                <video id="edit-siswa-webcam-{{ $siswa->id }}" autoplay playsinline class="absolute top-0 left-0 w-full h-full object-cover transform scale-x-[-1] hidden"></video>
                                                
                                                <div id="edit-webcam-overlay-{{ $siswa->id }}" class="absolute inset-x-0 inset-y-0 z-10 pointer-events-none flex items-center justify-center border-2 border-dashed border-white/50 rounded-xl px-12 py-10 m-6 hidden"></div>
                                                
                                                <div id="edit-webcam-starter-{{ $siswa->id }}" class="edit-webcam-starter z-20 text-white flex flex-col items-center cursor-pointer hover:text-blue-400 group p-4 text-center transition-colors" data-id="{{ $siswa->id }}">
                                                    <svg class="w-12 h-12 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                                    <span class="text-sm font-semibold">Klik untuk Aktifkan Kamera</span>
                                                    <span class="text-xs text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah wajah.</span>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Sampel Baru (<span id="edit-sample-count-text-{{ $siswa->id }}">0</span>/30)</span>
                                                    <span id="edit-camera-status-text-{{ $siswa->id }}" class="text-xs font-medium text-gray-500">Kamera Offline</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 overflow-hidden">
                                                    <div id="edit-sample-progress-bar-{{ $siswa->id }}" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                                                </div>
                                            </div>

                                            <button type="button" id="btn-start-record-edit-{{ $siswa->id }}" data-id="{{ $siswa->id }}" disabled class="btn-start-record-edit w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                Mulai Ambil Sampel Wajah Baru
                                            </button>
                                        </div>
                                    </div>
                                    <button type="submit" class="text-white w-full bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            @empty
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    Tidak ada data siswa yang ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $siswas->links() }}
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxAll = document.getElementById('checkbox-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const selectedCountSpan = document.getElementById('selected-count');

        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
            selectedCountSpan.textContent = checkedCount;
            
            if (checkedCount > 0) {
                bulkDeleteBtn.classList.remove('hidden');
                bulkDeleteBtn.classList.add('inline-flex');
            } else {
                bulkDeleteBtn.classList.add('hidden');
                bulkDeleteBtn.classList.remove('inline-flex');
            }
        }

        if(checkboxAll) {
            checkboxAll.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkDeleteButton();
            });
        }

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                if(checkboxAll) {
                    checkboxAll.checked = checkedCount === itemCheckboxes.length;
                }
                updateBulkDeleteButton();
            });
        });

        // --- NEW CAMERA SCRIPT FOR ADMIN STUDENT REGISTRATION --- //
        const webcamStarter = document.getElementById('webcam-starter');
        const videoElement = document.getElementById('siswa-webcam');
        const webcamOverlay = document.getElementById('webcam-overlay');
        const startRecordBtn = document.getElementById('btn-start-record');
        const sampleCountText = document.getElementById('sample-count-text');
        const sampleProgressBar = document.getElementById('sample-progress-bar');
        const cameraStatusText = document.getElementById('camera-status-text');
        const faceSamplesContainer = document.getElementById('face-samples-container');
        
        // Hide Modal close button logic handling
        const closeBtnObj = document.querySelectorAll('[data-modal-toggle="crud-modal-siswa"]');

        let stream = null;
        let maxSamples = 30; // 30 samples gives LBPH much better variance
        let currentSamples = 0;
        let isRecording = false;

        if(webcamStarter) {
            webcamStarter.addEventListener('click', async () => {
                webcamStarter.innerHTML = '<span class="text-white animate-pulse">Memuat Kamera...</span>';
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
                    videoElement.srcObject = stream;
                    videoElement.classList.remove('hidden');
                    webcamOverlay.classList.remove('hidden');
                    webcamStarter.classList.add('hidden');
                    startRecordBtn.disabled = false;
                    cameraStatusText.textContent = 'Kamera Aktif';
                    cameraStatusText.classList.remove('text-gray-500');
                    cameraStatusText.classList.add('text-green-500');
                } catch (err) {
                    console.error("Error accessing camera:", err);
                    webcamStarter.innerHTML = '<span class="text-red-500 font-bold">Gagal mengakses kamera.<br>Pastikan izin diberikan!</span>';
                }
            });

            startRecordBtn.addEventListener('click', () => {
                if(isRecording) return;
                isRecording = true;
                currentSamples = 0;
                faceSamplesContainer.innerHTML = ''; // reset inputs
                
                startRecordBtn.textContent = 'Mengambil Sampel...';
                startRecordBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                startRecordBtn.classList.add('bg-yellow-500', 'cursor-wait');

                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                
                const captureInterval = setInterval(() => {
                    if(currentSamples >= maxSamples) {
                        clearInterval(captureInterval);
                        isRecording = false;
                        startRecordBtn.textContent = 'Selesai (30 Sampel Tersimpan)';
                        startRecordBtn.classList.remove('bg-yellow-500', 'cursor-wait');
                        startRecordBtn.classList.add('bg-gray-500');
                        startRecordBtn.disabled = true;
                        
                        // Stop stream after taking samples if you want, 
                        // but it's usually better to keep it running until modal close so user knows it hasn't crashed
                        return;
                    }

                    canvas.width = videoElement.videoWidth;
                    canvas.height = videoElement.videoHeight;
                    context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
                    // Use JPEG for faster base64 size
                    const base64Image = canvas.toDataURL('image/jpeg', 0.8);

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'face_samples[]';
                    input.value = base64Image;
                    faceSamplesContainer.appendChild(input);

                    currentSamples++;
                    sampleCountText.textContent = currentSamples;
                    sampleProgressBar.style.width = (currentSamples / maxSamples * 100) + '%';
                    
                    // Visual feedback (flash border)
                    webcamOverlay.classList.remove('border-white/50');
                    webcamOverlay.classList.add('border-green-400', 'bg-green-400/20');
                    setTimeout(() => {
                        webcamOverlay.classList.add('border-white/50');
                        webcamOverlay.classList.remove('border-green-400', 'bg-green-400/20');
                    }, 150);

                }, 200); // Take sample every 200ms -> 30 samples in 6 seconds
            });

            // Clean up when modal is closed
            closeBtnObj.forEach(btn => {
                btn.addEventListener('click', () => {
                    if(stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                    }
                    videoElement.classList.add('hidden');
                    webcamOverlay.classList.add('hidden');
                    webcamStarter.classList.remove('hidden');
                    webcamStarter.innerHTML = '<svg class="w-12 h-12 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg><span class="text-sm font-semibold">Klik untuk Aktifkan Kamera</span><span class="text-xs text-gray-400 mt-1">Arahkan wajah siswa ke kamera untuk pendaftaran.</span>';
                    
                    startRecordBtn.disabled = true;
                    startRecordBtn.textContent = 'Mulai Ambil Sampel Wajah';
                    startRecordBtn.classList.remove('bg-yellow-500', 'bg-gray-500', 'cursor-wait');
                    startRecordBtn.classList.add('bg-green-600', 'hover:bg-green-700');

                    cameraStatusText.textContent = 'Kamera Offline';
                    cameraStatusText.classList.remove('text-green-500');
                    cameraStatusText.classList.add('text-gray-500');
                    
                    currentSamples = 0;
                    sampleCountText.textContent = '0';
                    sampleProgressBar.style.width = '0%';
                    faceSamplesContainer.innerHTML = '';
                    
                    // Reset form fields
                    document.getElementById('form-tambah-siswa').reset();
                });
            });
        }

        // --- NEW CAMERA SCRIPT FOR EDIT MODALS --- //
        const editWebcamStarters = document.querySelectorAll('.edit-webcam-starter');
        const editStartRecordBtns = document.querySelectorAll('.btn-start-record-edit');
        
        let editStream = null;
        let isEditRecording = false;

        editWebcamStarters.forEach(starter => {
            starter.addEventListener('click', async function() {
                const id = this.getAttribute('data-id');
                const videoElement = document.getElementById(`edit-siswa-webcam-${id}`);
                const webcamOverlay = document.getElementById(`edit-webcam-overlay-${id}`);
                const startRecordBtn = document.getElementById(`btn-start-record-edit-${id}`);
                const cameraStatusText = document.getElementById(`edit-camera-status-text-${id}`);

                this.innerHTML = '<span class="text-white animate-pulse">Memuat Kamera...</span>';
                
                try {
                    editStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
                    videoElement.srcObject = editStream;
                    videoElement.classList.remove('hidden');
                    webcamOverlay.classList.remove('hidden');
                    this.classList.add('hidden');
                    startRecordBtn.disabled = false;
                    cameraStatusText.textContent = 'Kamera Aktif';
                    cameraStatusText.classList.remove('text-gray-500');
                    cameraStatusText.classList.add('text-green-500');
                } catch (err) {
                    console.error("Error accessing camera:", err);
                    this.innerHTML = '<span class="text-red-500 font-bold">Gagal mengakses kamera.<br>Pastikan izin diberikan!</span>';
                }
            });
        });

        editStartRecordBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if(isEditRecording) return;
                isEditRecording = true;
                
                const id = this.getAttribute('data-id');
                const videoElement = document.getElementById(`edit-siswa-webcam-${id}`);
                const webcamOverlay = document.getElementById(`edit-webcam-overlay-${id}`);
                const faceSamplesContainer = document.getElementById(`edit-face-samples-container-${id}`);
                const sampleCountText = document.getElementById(`edit-sample-count-text-${id}`);
                const sampleProgressBar = document.getElementById(`edit-sample-progress-bar-${id}`);

                let currentSamples = 0;
                faceSamplesContainer.innerHTML = ''; 
                
                this.textContent = 'Mengambil Sampel...';
                this.classList.remove('bg-green-600', 'hover:bg-green-700');
                this.classList.add('bg-yellow-500', 'cursor-wait');

                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                
                const captureInterval = setInterval(() => {
                    if(currentSamples >= 30) {
                        clearInterval(captureInterval);
                        isEditRecording = false;
                        this.textContent = 'Selesai (30 Sampel Tersimpan)';
                        this.classList.remove('bg-yellow-500', 'cursor-wait');
                        this.classList.add('bg-gray-500');
                        this.disabled = true;
                        return;
                    }

                    canvas.width = videoElement.videoWidth;
                    canvas.height = videoElement.videoHeight;
                    context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
                    const base64Image = canvas.toDataURL('image/jpeg', 0.8);

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'face_samples[]';
                    input.value = base64Image;
                    faceSamplesContainer.appendChild(input);

                    currentSamples++;
                    sampleCountText.textContent = currentSamples;
                    sampleProgressBar.style.width = (currentSamples / 30 * 100) + '%';
                    
                    webcamOverlay.classList.remove('border-white/50');
                    webcamOverlay.classList.add('border-green-400', 'bg-green-400/20');
                    setTimeout(() => {
                        webcamOverlay.classList.add('border-white/50');
                        webcamOverlay.classList.remove('border-green-400', 'bg-green-400/20');
                    }, 150);

                }, 200);
            });
        });

        // Cleanup on edit modal close
        const editModalCloseBtns = document.querySelectorAll('[data-modal-toggle^="edit-modal-siswa-"]');
        editModalCloseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if(editStream) {
                    editStream.getTracks().forEach(track => track.stop());
                    editStream = null;
                }
            });
        });
    });
</script>
@endpush

@endsection
