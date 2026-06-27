<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // 1. Create Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Administrator',
            'role' => 'admin',
        ]);

        // 2. Create Main Guru for testing
        $mainGuruUser = User::create([
            'username' => '1234567890', // NIP as username
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Budi Santoso, S.Pd',
            'role' => 'guru',
        ]);

        $mainGuru = Guru::create([
            'user_id' => $mainGuruUser->id,
            'nip' => '1234567890',
            'mapel_ajar' => 'Matematika',
            'id_kelas_wali' => '6A',
        ]);

        // Create 20 Dummy Gurus
        $mapels = ['Matematika', 'IPA', 'IPS', 'Bahasa Indonesia', 'Bahasa Inggris', 'Seni Budaya', 'PJOK'];
        $kelas = ['1A', '2A', '3A', '4A', '5A', '6A', '1B', '2B', '3B', '4B', '5B', '6B'];
        
        for ($i = 0; $i < 20; $i++) {
            $nip = $faker->unique()->numerify('##########');
            $user = User::create([
                'username' => $nip,
                'password' => Hash::make('password'),
                'nama_lengkap' => $faker->name . ($faker->boolean ? ', S.Pd' : ''),
                'role' => 'guru',
            ]);
            Guru::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'mapel_ajar' => $faker->randomElement($mapels),
                'id_kelas_wali' => $faker->boolean(30) ? $faker->randomElement($kelas) : null,
            ]);
        }

        // 3. Create Main Siswa for testing
        $mainSiswaUser = User::create([
            'username' => '987654321',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Ahmad Reza',
            'role' => 'siswa',
        ]);
        Siswa::create([
            'user_id' => $mainSiswaUser->id,
            'nis' => '987654321',
            'id_kelas' => '6A',
        ]);

        // Create 50 Dummy Siswas
        for ($i = 0; $i < 50; $i++) {
            $nis = $faker->unique()->numerify('#########');
            $user = User::create([
                'username' => $nis,
                'password' => Hash::make('password'),
                'nama_lengkap' => $faker->name,
                'role' => 'siswa',
            ]);
            Siswa::create([
                'user_id' => $user->id,
                'nis' => $nis,
                'id_kelas' => $faker->randomElement($kelas),
            ]);
        }

        // 4. Create Dummy Materis for Main Guru
        $materiTypes = ['pdf', 'video'];
        $indonesianTopics = [
            'Pecahan dan Desimal', 'Sistem Tata Surya', 'Mengenal Pahlawan Nasional',
            'Puisi dan Pantun', 'Kingdom Animalia', 'Aljabar Dasar', 'Sejarah Kemerdekaan',
            'Seni Tari Tradisional', 'Senam Irama', 'Present Continuous Tense'
        ];

        for ($i = 1; $i <= 30; $i++) {
            \App\Models\Materi::create([
                'guru_id' => $mainGuru->id,
                'id_kelas' => $faker->randomElement(['6A', '6B', '5A', '4A']),
                'judul' => 'Materi: ' . $faker->randomElement($indonesianTopics) . ' - Bagian ' . $faker->numberBetween(1, 3),
                'type' => $faker->randomElement($materiTypes),
                'file_path' => 'dummy/path/to/file.pdf', // Dummy path
            ]);
        }

        // 5. Create Dummy Tugas for Main Guru
        $tugasInstructions = [
            'Silakan kerjakan soal-soal berikut dari buku paket halaman ',
            'Buatlah sebuah karangan singkat mengenai topik ini sepanjang minimal 3 paragraf.',
            'Tontonlah video materi yang telah diunggah, lalu rangkum poin-poin pentingnya.',
            'Kerjakan latihan soal di LKS Bab ',
            'Diskusikan dengan teman satu kelompokmu dan tuliskan hasil diskusinya dalam format PDF.',
            'Cari informasi tambahan di internet mengenai materi kita hari ini, lalu buatlah kesimpulan pribadimu.'
        ];

        for ($i = 1; $i <= 30; $i++) {
            \App\Models\Tugas::create([
                'guru_id' => $mainGuru->id,
                'id_kelas' => $faker->randomElement(['6A', '6B', '5A', '4A']),
                'judul' => 'Tugas: ' . $faker->randomElement($indonesianTopics) . ' - Latihan ' . $faker->numberBetween(1, 5),
                'instruksi' => $faker->randomElement($tugasInstructions) . ($faker->boolean ? $faker->numberBetween(10, 50) : '') . ".\n\nCatatan tambahan: " . $faker->realText(100),
                'deadline' => $faker->dateTimeBetween('-1 week', '+2 weeks'),
            ]);
        }
    }
}
