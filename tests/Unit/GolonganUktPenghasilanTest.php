<?php

namespace Tests\Unit;

use App\Models\GolonganUkt;
use Tests\TestCase;

class GolonganUktPenghasilanTest extends TestCase
{
    /**
     * @return array<string, array{0: int, 1: string}>
     */
    public static function penghasilanProvider(): array
    {
        return [
            'nol' => [0, 'I'],
            'batas atas golongan 1' => [2_999_999, 'I'],
            'batas bawah golongan 2' => [3_000_000, 'II'],
            'tengah golongan 2' => [4_500_000, 'II'],
            'batas atas golongan 2' => [4_999_999, 'II'],
            'batas bawah golongan 3' => [5_000_000, 'III'],
            'batas atas golongan 3' => [8_999_999, 'III'],
            'batas bawah golongan 5' => [9_000_000, 'V'],
            'di atas 9 juta' => [15_000_000, 'V'],
        ];
    }

    /**
     * @dataProvider penghasilanProvider
     */
    public function test_kode_golongan_sesuai_rentang_penghasilan(int $penghasilan, string $kode): void
    {
        $this->assertSame($kode, GolonganUkt::kodeDariPenghasilan($penghasilan));
    }
}
