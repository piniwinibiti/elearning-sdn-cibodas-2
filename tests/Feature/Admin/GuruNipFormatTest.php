<?php

namespace Tests\Feature\Admin;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GuruNipFormatTest extends TestCase
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
    }

    public function test_nip_beralfabet_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/guru', [
            'nama_lengkap' => 'Budi Santoso',
            'nip' => '1978A5122',
            'password' => 'rahasia123',
        ]);

        $response->assertSessionHasErrors('nip');
        $this->assertDatabaseMissing('gurus', ['nip' => '1978A5122']);
        $this->assertDatabaseMissing('users', ['username' => '1978A5122']);
    }

    public function test_nip_berupa_nama_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/guru', [
            'nama_lengkap' => 'Budi Santoso',
            'nip' => 'budi',
            'password' => 'rahasia123',
        ]);

        $response->assertSessionHasErrors('nip');
    }

    public function test_nip_digit_murni_diterima(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/guru', [
            'nama_lengkap' => 'Budi Santoso',
            'nip' => '197805122006041002',
            'password' => 'rahasia123',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('gurus', ['nip' => '197805122006041002']);
        $this->assertDatabaseHas('users', ['username' => '197805122006041002']);
    }

    public function test_update_tanpa_ubah_nip_leading_zero_tetap_utuh(): void
    {
        $user = User::create([
            'username' => '0993485667',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Guru Lama',
            'role' => 'guru',
        ]);

        $guru = Guru::create([
            'user_id' => $user->id,
            'nip' => '0993485667',
            'mapel_ajar' => '-',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/guru/{$guru->id}", [
            'nama_lengkap' => 'Guru Lama',
            'nip' => '0993485667',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('gurus', ['nip' => '0993485667']);
    }

    public function test_update_nip_beralfabet_ditolak_dan_nip_lama_tetap_utuh(): void
    {
        $user = User::create([
            'username' => '0993485667',
            'password' => Hash::make('password'),
            'nama_lengkap' => 'Guru Lama',
            'role' => 'guru',
        ]);

        $guru = Guru::create([
            'user_id' => $user->id,
            'nip' => '0993485667',
            'mapel_ajar' => '-',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/guru/{$guru->id}", [
            'nama_lengkap' => 'Guru Lama',
            'nip' => 'ganti-nip',
        ]);

        $response->assertSessionHasErrors('nip');
        $this->assertDatabaseHas('gurus', ['nip' => '0993485667']);
    }
}
