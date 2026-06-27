<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\SoalUjian;
use Exception;
use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    // ==========================================
    // SISWA ROUTES
    // ==========================================
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        // Find an active exam for this student's class
        $ujian = Ujian::with('soals')
            ->where('id_kelas', $siswa->id_kelas)
            ->latest()
            ->first();

        // If no exam is found, we'll still load the view but it will handle the empty state
        if(!$ujian) {
            return view('siswa.ujian.gateway', ['mataPelajaran' => 'Belum ada ujian', 'ujian' => null]);
        }

        return view('siswa.ujian.gateway', [
            'mataPelajaran' => $ujian->mata_pelajaran,
            'ujian' => $ujian
        ]);
    }

    // ==========================================
    // GURU ROUTES
    // ==========================================
    public function indexGuru()
    {
        $guruId = auth()->user()->guru->id;
        $ujians = Ujian::withCount('soals')->where('guru_id', $guruId)->latest()->paginate(10);
        $kelasOptions = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');
        $mapelOptions = Mapel::orderBy('nama_mapel')->pluck('nama_mapel');
        $guru = auth()->user()->guru;

        return view('guru.ujian.index', compact('ujians', 'kelasOptions', 'mapelOptions', 'guru'));
    }

    public function create()
    {
        $guru = auth()->user()->guru;
        $kelasOptions = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');
        $mapelOptions = Mapel::orderBy('nama_mapel')->pluck('nama_mapel');

        return view('guru.ujian.create', compact('guru', 'kelasOptions', 'mapelOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'id_kelas' => 'required|string|max:10',
            'mata_pelajaran' => 'required|string|max:255',
            'waktu_menit' => 'required|integer|min:1',
            'tipe' => 'required|in:ganda,essay',
        ]);

        if ($request->tipe == 'essay') {
            $request->validate([
                'teks_essay' => 'required',
                'file_soal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
            ]);
        } else {
            // Validasi soal objektif
            $request->validate([
                'soal' => 'required|array|min:1', // Keep this line for array validation
                'soal.*.pertanyaan' => 'required|string', // Changed to string
                'soal.*.opsi_a' => 'required|string', // Changed to string
                'soal.*.opsi_b' => 'required|string', // Changed to string
                'soal.*.opsi_c' => 'required|string', // Changed to string
                'soal.*.opsi_d' => 'required|string', // Changed to string
                'soal.*.jawaban_benar' => 'required|in:A,B,C,D',
            ]);
        }

        try {
            DB::beginTransaction();
            
            $fileSoalPath = null;
            if ($request->tipe == 'essay' && $request->hasFile('file_soal')) {
                $file = $request->file('file_soal');
                $filename = time() . '_' . auth()->user()->guru->id . '_soal_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/soal_ujian', $filename);
                $fileSoalPath = $filename;
            }

            $ujian = Ujian::create([
                'guru_id' => auth()->user()->guru->id,
                'id_kelas' => $request->id_kelas,
                'mata_pelajaran' => $request->mata_pelajaran,
                'judul' => $request->judul,
                'waktu_menit' => $request->waktu_menit,
                'tipe' => $request->tipe,
                'teks_essay' => $request->tipe == 'essay' ? $request->teks_essay : null,
                'file_soal' => $fileSoalPath,
            ]);

            if ($request->tipe == 'ganda') {
                foreach ($request->soal as $soalData) {
                    SoalUjian::create([
                        'ujian_id' => $ujian->id,
                        'pertanyaan' => $soalData['pertanyaan'],
                        'opsi_a' => $soalData['opsi_a'],
                        'opsi_b' => $soalData['opsi_b'],
                        'opsi_c' => $soalData['opsi_c'],
                        'opsi_d' => $soalData['opsi_d'],
                        'jawaban_benar' => $soalData['jawaban_benar'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil dibuat!');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan ujian: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $guru = auth()->user()->guru;
        $ujian = Ujian::with('soals')->where('guru_id', $guru->id)->findOrFail($id);
        
        $kelasOptions = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');
        $mapelOptions = Mapel::orderBy('nama_mapel')->pluck('nama_mapel');

        return view('guru.ujian.edit', compact('ujian', 'guru', 'kelasOptions', 'mapelOptions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'id_kelas' => 'required|string|max:10',
            'mata_pelajaran' => 'required|string|max:255',
            'waktu_menit' => 'required|integer|min:1',
            'tipe' => 'required|in:ganda,essay',
        ]);

        if ($request->tipe == 'ganda') {
            $request->validate([
                'soal' => 'required|array|min:1',
                'soal.*.pertanyaan' => 'required|string',
                'soal.*.opsi_a' => 'required|string',
                'soal.*.opsi_b' => 'required|string',
                'soal.*.opsi_c' => 'required|string',
                'soal.*.opsi_d' => 'required|string',
                'soal.*.jawaban_benar' => 'required|in:A,B,C,D'
            ]);
        } elseif ($request->tipe == 'essay') {
            $request->validate([
                'teks_essay' => 'required',
                'file_soal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
            ]);
        }
        
        try {
            $ujian = Ujian::where('guru_id', auth()->user()->guru->id)->findOrFail($id);
            
            $fileSoalPath = $ujian->file_soal; // Keep old file by default
            if ($request->tipe == 'essay' && $request->hasFile('file_soal')) {
                // If a new file is uploaded, store it
                $file = $request->file('file_soal');
                $filename = time() . '_' . auth()->user()->guru->id . '_soal_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/soal_ujian', $filename);
                $fileSoalPath = $filename;
            } elseif ($request->tipe == 'ganda') {
                // If changed to multiple choice, we could potentially delete the old file or just set null
                $fileSoalPath = null;
            }

            $ujian->update([
                'id_kelas' => $request->id_kelas,
                'mata_pelajaran' => $request->mata_pelajaran,
                'judul' => $request->judul,
                'waktu_menit' => $request->waktu_menit,
                'tipe' => $request->tipe,
                'teks_essay' => $request->tipe == 'essay' ? $request->teks_essay : null,
                'file_soal' => $fileSoalPath,
            ]);

            // Re-create the questions cleanly
            $ujian->soals()->delete();

            if ($request->tipe == 'ganda') {
                foreach ($request->soal as $soalData) {
                    SoalUjian::create([
                        'ujian_id' => $ujian->id,
                        'pertanyaan' => $soalData['pertanyaan'],
                        'opsi_a' => $soalData['opsi_a'],
                        'opsi_b' => $soalData['opsi_b'],
                        'opsi_c' => $soalData['opsi_c'],
                        'opsi_d' => $soalData['opsi_d'],
                        'jawaban_benar' => $soalData['jawaban_benar'],
                    ]);
                }
            }

            return redirect()->route('guru.ujian.index')->with('success', 'Ujian berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui ujian: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $ujian = Ujian::where('guru_id', auth()->user()->guru->id)->findOrFail($id);
        $ujian->delete();
        return back()->with('success', 'Ujian berhasil dihapus.');
    }

    public function submit(Request $request, $id)
    {
        $ujian = Ujian::findOrFail($id);
        
        // Cek tipe ujian
        if ($ujian->tipe == 'essay') {
            $request->validate([
                'file_jawaban' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
            ]);

            if ($request->hasFile('file_jawaban')) {
                $file = $request->file('file_jawaban');
                $filename = time() . '_' . auth()->user()->siswa->id . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/jawaban_essay', $filename);

                \App\Models\JawabanUjianEssay::create([
                    'ujian_id' => $ujian->id,
                    'siswa_id' => auth()->user()->siswa->id,
                    'file_path' => $filename,
                ]);

                return redirect()->route('siswa.ujian.index')->with('success', 'Jawaban ujian berhasil dikumpulkan!');
            }
        } else {
            // Logika untuk Pilihan Ganda
            $request->validate([
                'jawaban_*' => 'required|in:A,B,C,D'
            ]);

            foreach ($ujian->soals as $soal) {
                $inputName = 'jawaban_' . $soal->id;
                if ($request->has($inputName)) {
                    $jawabanSiswa = $request->input($inputName);
                    $isBenar = ($jawabanSiswa === $soal->jawaban_benar);

                    \App\Models\JawabanUjianGanda::updateOrCreate(
                        [
                            'ujian_id' => $ujian->id,
                            'siswa_id' => auth()->user()->siswa->id,
                            'soal_ujian_id' => $soal->id,
                        ],
                        [
                            'jawaban_siswa' => $jawabanSiswa,
                            'is_benar' => $isBenar,
                        ]
                    );
                }
            }

            return redirect()->route('siswa.ujian.index')->with('success', 'Ujian Pilihan Ganda berhasil diselesaikan! Nilai telah dihitung.');
        }

        return back()->with('error', 'Terjadi kesalahan saat mengumpulkan ujian.');
    }

    public function jawaban($id)
    {
        $ujian = Ujian::where('guru_id', auth()->user()->guru->id)->findOrFail($id);
        
        if($ujian->tipe == 'essay') {
            $jawabans = \App\Models\JawabanUjianEssay::with('siswa')->where('ujian_id', $id)->get();
            return view('guru.ujian.jawaban_essay', compact('ujian', 'jawabans'));
        }
        
        // Nanti bisa diextend untuk Pilihan Ganda kalau sudah ada fitur grading otomatisnya
        return back()->with('error', 'Fitur lihat nilai pilihan ganda belum tersedia.');
    }

    public function nilaiEssay(Request $request, $id)
    {
        $jawaban = \App\Models\JawabanUjianEssay::findOrFail($id);
        
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100'
        ]);

        $jawaban->update([
            'nilai' => $request->nilai
        ]);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
