<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FundFactSheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function user_can_view_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    /** @test */
    public function user_can_view_fund_fact_sheets()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/fund-fact-sheet');

        $response->assertStatus(200);
        $response->assertViewIs('fundfactsheet.index');
    }

    /** @test */
    public function user_cannot_upload_files()
    {
        $user = User::factory()->create(['role' => 'user']);
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($user)->post('/fund-fact-sheet', [
            'judul' => 'Test Fund Fact Sheet',
            'file' => $file,
            'tanggal_laporan' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_upload_files()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->actingAs($admin)->post('/fund-fact-sheet', [
            'judul' => 'Test Fund Fact Sheet',
            'file' => $file,
            'tanggal_laporan' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/fund-fact-sheet');
        $this->assertDatabaseHas('fund_fact_sheets', [
            'judul' => 'Test Fund Fact Sheet',
        ]);
    }

    /** @test */
    public function admin_can_delete_files()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $laporan = FundFactSheet::factory()->create([
            'uploaded_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->delete("/fund-fact-sheet/{$laporan->id}");

        $response->assertRedirect('/fund-fact-sheet');
        $this->assertDatabaseMissing('fund_fact_sheets', [
            'id' => $laporan->id,
        ]);
    }

    /** @test */
    public function superadmin_can_access_user_management()
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);

        $response = $this->actingAs($superadmin)->get('/user-management');

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    /** @test */
    public function admin_cannot_access_user_management()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/user-management');

        $response->assertStatus(403);
    }

    /** @test */
    public function file_must_be_pdf()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $file = UploadedFile::fake()->create('document.docx', 100);

        $response = $this->actingAs($admin)->post('/fund-fact-sheet', [
            'judul' => 'Test Fund Fact Sheet',
            'file' => $file,
            'tanggal_laporan' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('file');
    }

    /** @test */
    public function file_size_must_not_exceed_limit()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $file = UploadedFile::fake()->create('document.pdf', 20000); // 20MB

        $response = $this->actingAs($admin)->post('/fund-fact-sheet', [
            'judul' => 'Test Fund Fact Sheet',
            'file' => $file,
            'tanggal_laporan' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('file');
    }
}

// Run tests: php artisan test