<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\FundFactSheet;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_search_documents()
    {
        $user = User::factory()->create(['role' => 'user']);

        FundFactSheet::create([
            'judul' => 'Laporan Keuangan 2024',
            'file_path' => 'dummy.pdf',
            'tanggal_laporan' => '2024-01-01',
            'uploaded_by' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('search', ['keyword' => 'Keuangan']));

        $response->assertStatus(200);
        $response->assertSee('Laporan Keuangan 2024');
    }

    public function test_search_with_date_filter()
    {
        $user = User::factory()->create(['role' => 'user']);

        FundFactSheet::create([
            'judul' => 'Laporan Januari',
            'file_path' => 'dummy.pdf',
            'tanggal_laporan' => '2024-01-15',
            'uploaded_by' => $user->id,
        ]);

        FundFactSheet::create([
            'judul' => 'Laporan Maret',
            'file_path' => 'dummy.pdf',
            'tanggal_laporan' => '2024-03-15',
            'uploaded_by' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('search', [
                'start_date' => '2024-01-01',
                'end_date' => '2024-01-31'
            ]));

        $response->assertStatus(200);
        $response->assertSee('Laporan Januari');
        $response->assertDontSee('Laporan Maret');
    }

    public function test_api_search_endpoint()
    {
        $user = User::factory()->create(['role' => 'user']);

        FundFactSheet::create([
            'judul' => 'API Test Doc',
            'file_path' => 'dummy.pdf',
            'tanggal_laporan' => '2024-05-01',
            'uploaded_by' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/search?keyword=API');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'API Test Doc']);
    }
}
