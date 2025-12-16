<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FundFactSheet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_logs_upload_activity()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $this->actingAs($admin)->post('/fund-fact-sheet', [
            'judul' => 'Test Log Upload',
            'file' => $file,
            'tanggal_laporan' => now()->format('Y-m-d'),
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'created',
            'description' => 'Mengupload Fund Fact Sheet: Test Log Upload',
        ]);
    }

    /** @test */
    public function it_logs_update_activity()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $laporan = FundFactSheet::factory()->create([
            'uploaded_by' => $admin->id,
            'judul' => 'Old Title'
        ]);

        $this->actingAs($admin)->put("/fund-fact-sheet/{$laporan->id}", [
            'judul' => 'New Title',
            'tanggal_laporan' => now()->format('Y-m-d'),
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'updated',
            'model_id' => $laporan->id,
            'description' => 'Memperbarui Fund Fact Sheet: New Title',
        ]);
    }

    /** @test */
    public function it_logs_delete_activity()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $laporan = FundFactSheet::factory()->create([
            'uploaded_by' => $admin->id,
            'judul' => 'To Be Deleted'
        ]);

        $this->actingAs($admin)->delete("/fund-fact-sheet/{$laporan->id}");

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'deleted',
            'description' => 'Menghapus Fund Fact Sheet: To Be Deleted',
        ]);
    }

    /** @test */
    public function it_logs_download_activity()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $file = UploadedFile::fake()->create('document.pdf', 100);
        $path = $file->store('fund-fact-sheet', 'public');

        $laporan = FundFactSheet::factory()->create([
            'uploaded_by' => $admin->id,
            'judul' => 'Download Me',
            'file_path' => $path
        ]);

        // We need to visit the download route
        // Assuming route name is 'fundfactsheet.download'
        $response = $this->actingAs($admin)->get(route('fundfactsheet.download', $laporan->id));

        $response->assertStatus(200);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'downloaded',
            'description' => 'Mendownload Fund Fact Sheet: Download Me',
        ]);
    }
}
