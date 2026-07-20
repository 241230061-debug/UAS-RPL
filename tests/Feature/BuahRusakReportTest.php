<?php

namespace Tests\Feature;

use App\Models\Buah;
use App\Models\BuahRusak;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuahRusakReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_report_damaged_fruit_with_reason_and_reduce_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $buah = Buah::factory()->create(['stok' => 10, 'satuan' => 'kg']);

        $response = $this->actingAs($admin)->post(route('admin.buah.rusak', $buah), [
            'jumlah_rusak' => 3,
            'alasan' => 'Busuk karena lembab',
        ]);

        $response->assertRedirect(route('admin.buah.rusak.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('buah_rusak', [
            'buah_id' => $buah->id,
            'user_id' => $admin->id,
            'jumlah' => 3,
            'catatan' => 'Busuk karena lembab',
        ]);

        $this->assertSame(7, $buah->fresh()->stok);
    }
}
