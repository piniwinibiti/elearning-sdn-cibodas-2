<?php

namespace Tests\Feature\Admin;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SiswaNisFormatTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Admin Sekolah',
            'role' => 'admin',
        ]);

        Kelas::create(['nama_kelas' => '6A']);
    }

    public function test_nis_beralfabet_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/siswa', [
            'nama_lengkap' => 'Siti Aminah',
            'nis' => 'tidak ada',
            'id_kelas' => '6A',
            'password' => 'rahasia123',
        ]);

        $response->assertSessionHasErrors('nis');
        $this->assertDatabaseMissing('siswas', ['nis' => 'tidak ada']);
        $this->assertDatabaseMissing('users', ['username' => 'tidak ada']);
    }

    public function test_nis_digit_murni_diterima(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/siswa', [
            'nama_lengkap' => 'Siti Aminah',
            'nis' => '987654321',
            'id_kelas' => '6A',
            'password' => 'rahasia123',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('siswas', ['nis' => '987654321']);
        $this->assertDatabaseHas('users', ['username' => '987654321']);
    }

    public function test_update_tanpa_ubah_nis_tetap_utuh(): void
    {
        $user = User::create([
            'username' => '987654321',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Siswa Lama',
            'role' => 'siswa',
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nis' => '987654321',
            'id_kelas' => '6A',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/siswa/{$siswa->id}", [
            'nama_lengkap' => 'Siswa Lama',
            'nis' => '987654321',
            'id_kelas' => '6A',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('siswas', ['nis' => '987654321']);
    }

    public function test_update_nis_beralfabet_ditolak_dan_nis_lama_tetap_utuh(): void
    {
        $user = User::create([
            'username' => '987654321',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Siswa Lama',
            'role' => 'siswa',
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nis' => '987654321',
            'id_kelas' => '6A',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/siswa/{$siswa->id}", [
            'nama_lengkap' => 'Siswa Lama',
            'nis' => 'ganti-nis',
            'id_kelas' => '6A',
        ]);

        $response->assertSessionHasErrors('nis');
        $this->assertDatabaseHas('siswas', ['nis' => '987654321']);
    }
}
