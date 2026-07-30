@extends('layouts.app')

@section('title', 'Manajemen Kenaikan Kelas')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Kenaikan Kelas</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Proses kenaikan kelas massal berdasarkan rata-rata nilai siswa.</p>
    </div>

    <!-- Filter & KKM -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 p-6 mb-6">
        <form action="{{ route('admin.kenaikan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Kelas</label>
                <select name="kelas" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">-- Pilih Kelas Asal --</option>
                    @foreach($kelasOptions as $ko)
                        <option value="{{ $ko->nama_kelas }}" {{ $selectedKelas == $ko->nama_kelas ? 'selected' : '' }}>Kelas {{ $ko->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Standar Kelulusan (KKM)</label>
                <input type="number" name="kkm" value="{{ $kkm }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">Tampilkan Data Siswa</button>
            </div>
        </form>
    </div>

    @if($selectedKelas)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
        <form action="{{ route('admin.kenaikan.process') }}" method="POST">
            @csrf
            <input type="hidden" name="current_kelas" value="{{ $selectedKelas }}">
            
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Siswa Kelas {{ $selectedKelas }}</h2>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">
                        Pindahkan ke Kelas:
                    </div>
                    <select name="target_kelas" required class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- Pilih Kelas Tujuan --</option>
                        @foreach($kelasOptions as $ko)
                            <option value="{{ $ko->nama_kelas }}" {{ old('target_kelas') == $ko->nama_kelas ? 'selected' : '' }}>Kelas {{ $ko->nama_kelas }}</option>
                        @endforeach
                        <option value="LULUS" {{ old('target_kelas') == 'LULUS' ? 'selected' : '' }}>LULUS / ALUMNI</option>
                    </select>
                    <button type="submit" onclick="return confirm('Yakin ingin memproses kenaikan kelas untuk siswa terpilih?')" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-bold rounded-lg text-sm px-5 py-2 transition-colors">
                        Proses Kenaikan
                    </button>
                </div>
            </div>
            <div class="px-4 pb-2">
                <x-input-error name="target_kelas" />
                <x-input-error :messages="$errors->get('siswa_ids')" />
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="p-4 w-4">
                                <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </th>
                            <th class="px-6 py-3">NIS & Nama Siswa</th>
                            <th class="px-6 py-3">Rata-rata Ujian</th>
                            <th class="px-6 py-3">Rata-rata Tugas</th>
                            <th class="px-6 py-3">Total Akumulasi</th>
                            <th class="px-6 py-3">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($siswas as $s)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="p-4 w-4">
                                <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}" {{ $s->is_recommended ? 'checked' : '' }} class="siswa-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                <div class="flex flex-col">
                                    <span>{{ $s->nama }}</span>
                                    <span class="text-xs text-gray-500 font-normal">NIS: {{ $s->nis }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $s->avg_ujian }}</td>
                            <td class="px-6 py-4">{{ $s->avg_tugas }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold {{ $s->total_avg >= $kkm ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $s->total_avg }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($s->is_recommended)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">NAIK KELAS</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">TETAP / PERLU EVALUASI</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data siswa di kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.getElementById('select-all')?.addEventListener('change', function(e) {
        document.querySelectorAll('.siswa-checkbox').forEach(cb => {
            cb.checked = e.target.checked;
        });
    });
</script>
@endpush
@endsection
