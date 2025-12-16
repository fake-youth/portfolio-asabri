<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\DocumentCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Seed database to ensure roles exist if handled by seeder
        $this->seed();
    }

    private function createAdmin()
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function createSuperAdmin()
    {
        return User::factory()->create(['role' => 'superadmin']);
    }

    private function createUser()
    {
        return User::factory()->create(['role' => 'user']);
    }

    public function test_admin_can_view_category_index_page()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('document-categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Kategori Dokumen');
    }

    public function test_user_cannot_view_category_index_page()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->get(route('document-categories.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_document_category()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->post(route('document-categories.store'), [
                'type' => 'fund_fact_sheet',
                'title' => 'Test Category',
                'manager' => 'Test Manager',
                'description' => 'Test Description',
                'order' => 1,
            ]);

        $response->assertRedirect(route('document-categories.index'));
        $this->assertDatabaseHas('document_categories', [
            'type' => 'fund_fact_sheet',
            'title' => 'Test Category',
            'manager' => 'Test Manager',
        ]);
    }

    public function test_admin_can_update_document_category()
    {
        $admin = $this->createAdmin();
        $category = DocumentCategory::create([
            'type' => 'fund_fact_sheet',
            'title' => 'Old Title',
            'created_by' => $admin->id,
            'is_active' => true
        ]);

        $response = $this->actingAs($admin)
            ->put(route('document-categories.update', $category->id), [
                'type' => 'fund_fact_sheet',
                'title' => 'New Title',
            ]);

        $response->assertRedirect(route('document-categories.index'));
        $this->assertDatabaseHas('document_categories', [
            'id' => $category->id,
            'title' => 'New Title',
        ]);
    }

    public function test_admin_can_toggle_status()
    {
        $admin = $this->createAdmin();
        $category = DocumentCategory::create([
            'type' => 'fund_fact_sheet',
            'title' => 'Test Toggle',
            'created_by' => $admin->id,
            'is_active' => true
        ]);

        $response = $this->actingAs($admin)
            ->post(route('document-categories.toggle', $category->id));

        $response->assertRedirect(route('document-categories.index'));
        $this->assertDatabaseHas('document_categories', [
            'id' => $category->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_category()
    {
        $admin = $this->createAdmin();
        $category = DocumentCategory::create([
            'type' => 'fund_fact_sheet',
            'title' => 'Test Delete',
            'created_by' => $admin->id,
            'is_active' => true
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('document-categories.destroy', $category->id));

        $response->assertRedirect(route('document-categories.index'));
        $this->assertDatabaseMissing('document_categories', [
            'id' => $category->id,
        ]);
    }

    public function test_api_can_fetch_categories()
    {
        $user = $this->createUser();
        $category = DocumentCategory::create([
            'type' => 'fund_fact_sheet',
            'title' => 'API Test Category',
            'created_by' => $user->id,
            'is_active' => true
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/document-categories');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'API Test Category',
            ]);
    }
}
