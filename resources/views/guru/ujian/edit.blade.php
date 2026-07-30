@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between pointer-events-none">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Edit Ujian</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Modifikasi pengaturan dan soal ujian Face ID.</p>
    </div>
    <a href="{{ route('guru.ujian.index') }}" class="pointer-events-auto text-sm font-medium text-blue-600 hover:underline dark:text-blue-500 flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali
    </a>
</div>

<form action="{{ route('guru.ujian.update', $ujian->id) }}" method="POST" id="form-ujian" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- Informasi Ujian (Header) -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 border-b pb-2 dark:border-gray-700">Pengaturan Dasar Ujian</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="col-span-1 md:col-span-2 lg:col-span-2">
                <label for="judul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul/Mata Pelajaran <span class="text-red-500">*</span></label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $ujian->judul) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <x-input-error name="judul" />
            </div>

            @if($guru->id_kelas_wali)
                {{-- GURU KELAS: Lock Kelas, Open Mapel --}}
                <div class="col-span-1">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas Target</label>
                    <input type="text" value="Kelas {{ $guru->id_kelas_wali }}" class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400" readonly>
                    <input type="hidden" name="id_kelas" value="{{ $guru->id_kelas_wali }}">
                </div>
                <div class="col-span-1">
                    <label for="mata_pelajaran" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select name="mata_pelajaran" id="mata_pelajaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        @foreach($mapelOptions as $mapel)
                            <option value="{{ $mapel }}" {{ old('mata_pelajaran', $ujian->mata_pelajaran) == $mapel ? 'selected' : '' }}>{{ $mapel }}</option>
                        @endforeach
                    </select>
                    <x-input-error name="mata_pelajaran" />
                </div>
            @else
                {{-- GURU SPESIALIS: Open Kelas, Lock Mapel --}}
                <div>
                    <label for="id_kelas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas Target <span class="text-red-500">*</span></label>
                    <select name="id_kelas" id="id_kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option value="" disabled>Pilih kelas...</option>
                        @foreach($kelasOptions as $kls)
                            <option value="{{ $kls }}" {{ old('id_kelas', $ujian->id_kelas) == $kls ? 'selected' : '' }}>Kelas {{ $kls }}</option>
                        @endforeach
                    </select>
                    <x-input-error name="id_kelas" />
                </div>
                <div>
                    <label for="mata_pelajaran" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select name="mata_pelajaran" id="mata_pelajaran" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        @foreach($mapelOptions as $mapel)
                            <option value="{{ $mapel }}" {{ old('mata_pelajaran', $ujian->mata_pelajaran) == $mapel ? 'selected' : '' }}>{{ $mapel }}</option>
                        @endforeach
                    </select>
                    <x-input-error name="mata_pelajaran" />
                </div>
            @endif

            <div>
                <label for="waktu_menit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Limit Waktu (Menit) <span class="text-red-500">*</span></label>
                <input type="number" name="waktu_menit" id="waktu_menit" min="1" value="{{ old('waktu_menit', $ujian->waktu_menit) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <x-input-error name="waktu_menit" />
            </div>

            <div class="col-span-1 md:col-span-2 lg:col-span-4 mt-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe Ujian <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="tipe" value="ganda" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ old('tipe', $ujian->tipe) == 'ganda' ? 'checked' : '' }} onchange="toggleTipeUjian()">
                        <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Pilihan Ganda</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="tipe" value="essay" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ old('tipe', $ujian->tipe) == 'essay' ? 'checked' : '' }} onchange="toggleTipeUjian()">
                        <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Essay / Take Home (File Upload)</span>
                    </label>
                </div>
                <x-input-error name="tipe" />
            </div>
        </div>
    </div>

    <!-- Essay/Take Home Input -->
    <div id="essay-container" style="display: {{ $ujian->tipe == 'essay' ? 'block' : 'none' }};" class="mb-6 p-4 md:p-6 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-700/50 dark:border-gray-600">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            Instruksi / Soal Essay
        </h3>
        <p class="text-sm text-gray-500 mb-4">Tuliskan instruksi atau soal secara lengkap. Siswa nantinya akan diminta mengunggah file gambar/PDF sebagai jawaban mereka.</p>
        <textarea name="teks_essay" id="teks_essay" rows="6" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-4" placeholder="Contoh: 1. Jelaskan menurut pendapat Anda mengenai fotosintesis...">{{ old('teks_essay', $ujian->teks_essay) }}</textarea>
        <x-input-error name="teks_essay" />

        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_soal">File Pendukung Ujian (PDF / Gambar) <span class="text-gray-500 font-normal italic">(Opsional)</span></label>
            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:text-gray-400 focus:outline-none dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400" id="file_soal" name="file_soal" type="file" accept=".pdf,image/*">
            <x-input-error name="file_soal" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih file materi ujian kalau ingin diubah. Abaikan jika tidak. Maksimal 5MB.</p>
            
            @if($ujian->file_soal)
                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-between border border-blue-100 dark:border-blue-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        <span class="text-sm font-medium text-blue-900 dark:text-blue-200">File Saat Ini:</span>
                    </div>
                    <a href="{{ Storage::url('soal_ujian/' . $ujian->file_soal) }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center">
                        Lihat File
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Dynamic Questions List -->
    <div id="ganda-header" class="mb-4 flex items-center justify-between pointer-events-none">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Soal</h2>
        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800" id="soal-counter">{{ count($ujian->soals) }} Soal</span>
    </div>

    <div id="soal-container" class="space-y-6">
        @foreach($ujian->soals as $index => $soal)
        <div class="soal-item bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6 relative">
            <div class="absolute top-4 right-4">
                <button type="button" class="btn-hapus-soal {{ count($ujian->soals) > 1 ? '' : 'hidden' }} text-gray-400 hover:text-red-600 transition-colors" title="Hapus Soal Ini">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
            
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                <span class="soal-number border-2 border-blue-600 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">{{ $index + 1 }}</span>
                Pertanyaan Soal
            </h3>
            
            <div class="mb-4">
                <textarea name="soal[{{ $index }}][pertanyaan]" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tuliskan pertanyaan di sini..." required>{{ old("soal.{$index}.pertanyaan", $soal->pertanyaan) }}</textarea>
                <x-input-error :messages="$errors->get('soal.'.$index.'.pertanyaan')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                        <span class="w-6 text-center font-bold mr-2 text-gray-500">A</span> Pilihan Ganda A
                    </label>
                    <input type="text" name="soal[{{ $index }}][opsi_a]" value="{{ old("soal.{$index}.opsi_a", $soal->opsi_a) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    <x-input-error :messages="$errors->get('soal.'.$index.'.opsi_a')" />
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                        <span class="w-6 text-center font-bold mr-2 text-gray-500">B</span> Pilihan Ganda B
                    </label>
                    <input type="text" name="soal[{{ $index }}][opsi_b]" value="{{ old("soal.{$index}.opsi_b", $soal->opsi_b) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    <x-input-error :messages="$errors->get('soal.'.$index.'.opsi_b')" />
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                        <span class="w-6 text-center font-bold mr-2 text-gray-500">C</span> Pilihan Ganda C
                    </label>
                    <input type="text" name="soal[{{ $index }}][opsi_c]" value="{{ old("soal.{$index}.opsi_c", $soal->opsi_c) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    <x-input-error :messages="$errors->get('soal.'.$index.'.opsi_c')" />
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                        <span class="w-6 text-center font-bold mr-2 text-gray-500">D</span> Pilihan Ganda D
                    </label>
                    <input type="text" name="soal[{{ $index }}][opsi_d]" value="{{ old("soal.{$index}.opsi_d", $soal->opsi_d) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    <x-input-error :messages="$errors->get('soal.'.$index.'.opsi_d')" />
                </div>
            </div>

            <div class="mt-4 pt-4 border-t dark:border-gray-700">
                <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Kunci Jawaban Benar <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-4">
                    @foreach(['A', 'B', 'C', 'D'] as $opsi)
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="soal[{{ $index }}][jawaban_benar]" value="{{ $opsi }}" {{ old("soal.{$index}.jawaban_benar", $soal->jawaban_benar) == $opsi ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600" required>
                        <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $opsi }}</span>
                    </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('soal.'.$index.'.jawaban_benar')" />
            </div>
        </div>
        @endforeach
    </div>
    <x-input-error :messages="$errors->get('soal')" />

    <!-- Controls Bottom -->
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-6">
        <button type="button" id="btn-tambah-soal" class="w-full sm:w-auto text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-4 sm:mb-0 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800 transition-colors inline-flex items-center justify-center">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Baris Soal
        </button>
        
        <button type="submit" class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-8 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors shadow-md">
            Perbarui Ujian & Soal
        </button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('soal-container');
        const btnTambah = document.getElementById('btn-tambah-soal');
        const counterUI = document.getElementById('soal-counter');
        
        let soalIndex = {{ count($ujian->soals) }}; 
        
        function updateCounters() {
            const items = document.querySelectorAll('.soal-item');
            counterUI.textContent = items.length + " Soal";
            
            items.forEach((item, index) => {
                item.querySelector('.soal-number').textContent = index + 1;
                const deleteBtn = item.querySelector('.btn-hapus-soal');
                if (items.length > 1) {
                    deleteBtn.classList.remove('hidden');
                } else {
                    deleteBtn.classList.add('hidden');
                }
            });
        }

        btnTambah.addEventListener('click', function() {
            soalIndex++;
            const newItemHTML = `
                <div class="soal-item animate-fade-in bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6 relative">
                    <div class="absolute top-4 right-4">
                        <button type="button" class="btn-hapus-soal text-gray-400 hover:text-red-600 transition-colors" title="Hapus Soal Ini">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <span class="soal-number border-2 border-blue-600 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm"></span>
                        Pertanyaan Soal
                    </h3>
                    
                    <div class="mb-4">
                        <textarea name="soal[${soalIndex}][pertanyaan]" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tuliskan pertanyaan di sini..." required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                <span class="w-6 text-center font-bold mr-2 text-gray-500">A</span> Pilihan Ganda A
                            </label>
                            <input type="text" name="soal[${soalIndex}][opsi_a]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                <span class="w-6 text-center font-bold mr-2 text-gray-500">B</span> Pilihan Ganda B
                            </label>
                            <input type="text" name="soal[${soalIndex}][opsi_b]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                <span class="w-6 text-center font-bold mr-2 text-gray-500">C</span> Pilihan Ganda C
                            </label>
                            <input type="text" name="soal[${soalIndex}][opsi_c]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                <span class="w-6 text-center font-bold mr-2 text-gray-500">D</span> Pilihan Ganda D
                            </label>
                            <input type="text" name="soal[${soalIndex}][opsi_d]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t dark:border-gray-700">
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Kunci Jawaban Benar <span class="text-red-500">*</span></label>
                        <div class="flex flex-wrap gap-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="soal[${soalIndex}][jawaban_benar]" value="A" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600" required>
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">A</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="soal[${soalIndex}][jawaban_benar]" value="B" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600" required>
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">B</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="soal[${soalIndex}][jawaban_benar]" value="C" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600" required>
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">C</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="soal[${soalIndex}][jawaban_benar]" value="D" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600" required>
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">D</span>
                            </label>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newItemHTML);
            updateCounters();
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.btn-hapus-soal')) {
                const item = e.target.closest('.soal-item');
                if (document.querySelectorAll('.soal-item').length > 1) {
                    item.classList.add('transition-opacity', 'duration-300', 'opacity-0');
                    setTimeout(() => {
                        item.remove();
                        updateCounters();
                    }, 300);
                }
            }
        });
        
        updateCounters();
    });

    // Toggle logic for Essay vs Pilihan Ganda
    function toggleTipeUjian() {
        const checkedTipe = document.querySelector('input[name="tipe"]:checked');
        if(!checkedTipe) return;
        const isEssay = checkedTipe.value === 'essay';
        
        const essayContainer = document.getElementById('essay-container');
        const gandaHeader = document.getElementById('ganda-header');
        const gandaContainer = document.getElementById('soal-container');
        const btnTambah = document.getElementById('btn-tambah-soal');
        const teksEssay = document.getElementById('teks_essay');

        if (isEssay) {
            essayContainer.classList.remove('hidden');
            gandaHeader.classList.add('hidden');
            gandaContainer.classList.add('hidden');
            btnTambah.classList.add('hidden');
            
            // Toggle required states
            teksEssay.setAttribute('required', 'required');
            gandaContainer.querySelectorAll('input, textarea').forEach(el => el.removeAttribute('required'));
        } else {
            essayContainer.classList.add('hidden');
            gandaHeader.classList.remove('hidden');
            gandaContainer.classList.remove('hidden');
            btnTambah.classList.remove('hidden');
            
            // Toggle required states
            teksEssay.removeAttribute('required');
            gandaContainer.querySelectorAll('input, textarea').forEach(el => {
                if(el.type !== 'radio' || el.checked || !el.closest('.flex-wrap').querySelector(':checked')) {
                   if(el.type !== 'button') el.setAttribute('required', 'required');
                }
            });
        }
    }
    
    // Run once on load
    document.addEventListener('DOMContentLoaded', toggleTipeUjian);

    // Add event listeners for tipe ujian radio buttons
    document.querySelectorAll('input[name="tipe"]').forEach(radio => {
        radio.addEventListener('change', toggleTipeUjian);
    });
</script>
<style>
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
@endsection
