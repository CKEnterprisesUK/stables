<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\Horse;
use App\Models\HorsePhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HorseControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => UserRole::Admin]);
    }

    public function test_index_displays_all_horses(): void
    {
        $horses = Horse::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.horses.index'));

        $response->assertOk();
        foreach ($horses as $horse) {
            $response->assertSee($horse->name);
        }
    }

    public function test_index_requires_admin_role(): void
    {
        $sponsor = User::factory()->create(['role' => UserRole::Sponsor]);

        $response = $this->actingAs($sponsor)->get(route('admin.horses.index'));

        $response->assertForbidden();
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.horses.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_create_form_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.horses.create'));

        $response->assertOk();
        $response->assertSee('Add Horse');
    }

    public function test_store_creates_horse_with_photos(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('admin.horses.store'), [
            'name' => 'Thunderbolt',
            'facts' => 'A fast horse.',
            'photos' => [
                UploadedFile::fake()->image('photo1.jpg'),
                UploadedFile::fake()->image('photo2.jpg'),
            ],
        ]);

        $response->assertRedirect(route('admin.horses.index'));

        $this->assertDatabaseHas('horses', [
            'name' => 'Thunderbolt',
            'facts' => 'A fast horse.',
        ]);

        $horse = Horse::where('name', 'Thunderbolt')->first();
        $this->assertCount(2, $horse->photos);

        // Verify files were stored
        foreach ($horse->photos as $photo) {
            Storage::disk('public')->assertExists($photo->path);
        }
    }

    public function test_store_validates_required_name(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.horses.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_store_validates_photo_type(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('admin.horses.store'), [
            'name' => 'Test Horse',
            'photos' => [
                UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            ],
        ]);

        $response->assertSessionHasErrors('photos.0');
    }

    public function test_show_displays_horse_details(): void
    {
        $horse = Horse::factory()->create(['name' => 'Star']);

        $response = $this->actingAs($this->admin)->get(route('admin.horses.show', $horse));

        $response->assertOk();
        $response->assertSee('Star');
    }

    public function test_edit_displays_horse_form(): void
    {
        $horse = Horse::factory()->create(['name' => 'Lightning']);

        $response = $this->actingAs($this->admin)->get(route('admin.horses.edit', $horse));

        $response->assertOk();
        $response->assertSee('Lightning');
    }

    public function test_update_modifies_horse_data(): void
    {
        $horse = Horse::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin)->put(route('admin.horses.update', $horse), [
            'name' => 'New Name',
            'facts' => 'Updated facts.',
        ]);

        $response->assertRedirect(route('admin.horses.index'));

        $this->assertDatabaseHas('horses', [
            'id' => $horse->id,
            'name' => 'New Name',
            'facts' => 'Updated facts.',
        ]);
    }

    public function test_update_adds_new_photos(): void
    {
        Storage::fake('public');

        $horse = Horse::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.horses.update', $horse), [
            'name' => $horse->name,
            'photos' => [
                UploadedFile::fake()->image('new_photo.jpg'),
            ],
        ]);

        $response->assertRedirect(route('admin.horses.index'));
        $this->assertCount(1, $horse->fresh()->photos);
    }

    public function test_destroy_deletes_horse_and_photos(): void
    {
        Storage::fake('public');

        $horse = Horse::factory()->create();

        // Upload a photo
        $path = UploadedFile::fake()->image('photo.jpg')->store('horses', 'public');
        $horse->photos()->create(['path' => $path, 'sort_order' => 1]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete(route('admin.horses.destroy', $horse));

        $response->assertRedirect(route('admin.horses.index'));

        $this->assertDatabaseMissing('horses', ['id' => $horse->id]);
        $this->assertDatabaseMissing('horse_photos', ['horse_id' => $horse->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_store_creates_horse_without_photos(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.horses.store'), [
            'name' => 'No Photos Horse',
            'facts' => 'Just facts.',
        ]);

        $response->assertRedirect(route('admin.horses.index'));
        $this->assertDatabaseHas('horses', ['name' => 'No Photos Horse']);
    }

    public function test_update_deletes_selected_photos(): void
    {
        Storage::fake('public');

        $horse = Horse::factory()->create();

        // Create two photos
        $path1 = UploadedFile::fake()->image('photo1.jpg')->store('horses', 'public');
        $path2 = UploadedFile::fake()->image('photo2.jpg')->store('horses', 'public');
        $photo1 = $horse->photos()->create(['path' => $path1, 'sort_order' => 1]);
        $photo2 = $horse->photos()->create(['path' => $path2, 'sort_order' => 2]);

        Storage::disk('public')->assertExists($path1);
        Storage::disk('public')->assertExists($path2);

        // Delete only the first photo
        $response = $this->actingAs($this->admin)->put(route('admin.horses.update', $horse), [
            'name' => $horse->name,
            'delete_photos' => [$photo1->id],
        ]);

        $response->assertRedirect(route('admin.horses.index'));

        // First photo deleted
        $this->assertDatabaseMissing('horse_photos', ['id' => $photo1->id]);
        Storage::disk('public')->assertMissing($path1);

        // Second photo still exists
        $this->assertDatabaseHas('horse_photos', ['id' => $photo2->id]);
        Storage::disk('public')->assertExists($path2);
    }
}
