<?php

namespace Tests\Unit\Rules;

use App\Rules\DigitsOnly;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DigitsOnlyTest extends TestCase
{
    private function validate(mixed $value): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make(
            ['nip' => $value],
            ['nip' => [new DigitsOnly('NIP')]],
        );
    }

    public function test_menerima_digit_murni(): void
    {
        $this->assertTrue($this->validate('1234567890')->passes());
    }

    public function test_menerima_leading_zero_tanpa_mengubah_nilai(): void
    {
        $validator = $this->validate('0993485667');

        $this->assertTrue($validator->passes());
        $this->assertSame('0993485667', $validator->validated()['nip']);
    }

    public function test_menerima_nip_18_digit(): void
    {
        $this->assertTrue($this->validate('197805122006041002')->passes());
    }

    public function test_menolak_huruf(): void
    {
        $this->assertTrue($this->validate('budi')->fails());
    }

    public function test_menolak_huruf_di_tengah(): void
    {
        $this->assertTrue($this->validate('1978A5122')->fails());
    }

    public function test_menolak_tanda_minus(): void
    {
        $this->assertTrue($this->validate('-12345')->fails());
    }

    public function test_menolak_tanda_plus(): void
    {
        $this->assertTrue($this->validate('+12345')->fails());
    }

    public function test_menolak_desimal(): void
    {
        $this->assertTrue($this->validate('1.5')->fails());
    }

    public function test_menolak_notasi_ilmiah(): void
    {
        $this->assertTrue($this->validate('1e5')->fails());
    }

    public function test_menolak_notasi_heksadesimal(): void
    {
        $this->assertTrue($this->validate('0x1A')->fails());
    }

    public function test_menolak_spasi_di_tengah(): void
    {
        $this->assertTrue($this->validate('123 456')->fails());
    }

    public function test_menolak_array_tanpa_type_error(): void
    {
        $validator = $this->validate(['1', '2']);

        $this->assertTrue($validator->fails());
    }
}
