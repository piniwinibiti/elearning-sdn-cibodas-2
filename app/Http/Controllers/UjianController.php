<?php

namespace App\Http\Controllers;

use App\Http\Requests\NilaiEssayRequest;
use App\Http\Requests\StoreUjianRequest;
use App\Http\Requests\SubmitUjianRequest;
use App\Http\Requests\UpdateUjianRequest;
use App\Models\JawabanUjianEssay;
use App\Models\JawabanUjianGanda;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\SoalUjian;
use App\Models\Ujian;
use Exception;
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
        if (! $ujian) {
            return view('siswa.ujian.gateway', ['mataPelajaran' => 'Belum ada ujian', 'ujian' => null]);
        }

        return view('siswa.ujian.gateway', [
            'mataPelajaran' => $ujian->mata_pelajaran,
            'ujian' => $ujian,
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

    public function store(StoreUjianRequest $request)
    {
        try {
            DB::beginTransaction();

            $fileSoalPath = null;
            if ($request->tipe == 'essay' && $request->hasFile('file_soal')) {
                $file = $request->file('file_soal');
                $filename = time().'_'.auth()->user()->guru->id.'_soal_'.$file->getClientOriginalName();
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
            return back()->with('error', 'Terjadi kesalahan saat menyimpan ujian: '.$e->getMessage());
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

    public function update(UpdateUjianRequest $request, $id)
    {
        try {
            $ujian = Ujian::where('guru_id', auth()->user()->guru->id)->findOrFail($id);

            $fileSoalPath = $ujian->file_soal; // Keep old file by default
            if ($request->tipe == 'essay' && $request->hasFile('file_soal')) {
                // If a new file is uploaded, store it
                $file = $request->file('file_soal');
                $filename = time().'_'.auth()->user()->guru->id.'_soal_'.$file->getClientOriginalName();
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
            return back()->with('error', 'Terjadi kesalahan saat memperbarui ujian: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        $ujian = Ujian::where('guru_id', auth()->user()->guru->id)->findOrFail($id);
        $ujian->delete();

        return back()->with('success', 'Ujian berhasil dihapus.');
    }

    public function submit(SubmitUjianRequest $request, $id)
    {
        $ujian = Ujian::findOrFail($id);

        // Cek tipe ujian
        if ($ujian->tipe == 'essay') {
            $file = $request->file('file_jawaban');
            $filename = time().'_'.auth()->user()->siswa->id.'_'.$file->getClientOriginalName();
            $path = $file->storeAs('public/jawaban_essay', $filename);

            JawabanUjianEssay::create([
                'ujian_id' => $ujian->id,
                'siswa_id' => auth()->user()->siswa->id,
                'file_path' => $filename,
            ]);

            return redirect()->route('siswa.ujian.index')->with('success', 'Jawaban ujian berhasil dikumpulkan!');
        }

        // Validasi sudah dijamin SubmitUjianRequest: semua soal terjawab
        // dengan nilai A/B/C/D. Lihat docs/analysis/...-04-... §4.4
        $jawabanInput = $request->validated()['jawaban'];

        foreach ($ujian->soals as $soal) {
            $jawabanSiswa = $jawabanInput[$soal->id];
            $isBenar = ($jawabanSiswa === $soal->jawaban_benar);

            JawabanUjianGanda::updateOrCreate(
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

        return redirect()->route('siswa.ujian.index')->with('success', 'Ujian Pilihan Ganda berhasil diselesaikan! Nilai telah dihitung.');
    }

    public function jawaban($id)
    {
        $ujian = Ujian::where('guru_id', auth()->user()->guru->id)->findOrFail($id);

        if ($ujian->tipe == 'essay') {
            $jawabans = JawabanUjianEssay::with('siswa')->where('ujian_id', $id)->get();

            return view('guru.ujian.jawaban_essay', compact('ujian', 'jawabans'));
        }

        // Nanti bisa diextend untuk Pilihan Ganda kalau sudah ada fitur grading otomatisnya
        return back()->with('error', 'Fitur lihat nilai pilihan ganda belum tersedia.');
    }

    public function nilaiEssay(NilaiEssayRequest $request, $id)
    {
        $jawaban = JawabanUjianEssay::findOrFail($id);

        $jawaban->update([
            'nilai' => $request->nilai,
        ]);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
