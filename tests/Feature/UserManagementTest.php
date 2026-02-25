<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an admin user
        $this->admin = User::factory()->create(['role' => 'she']);
    }

    public function test_can_create_user_with_npk_and_position()
    {
        $response = $this->actingAs($this->admin)->post(route('she.users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'npk' => '12345678',
            'position' => 'Staff',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'karyawan',
        ]);

        $response->assertRedirect(route('she.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'npk' => '12345678',
            'position' => 'Staff',
        ]);
    }

    public function test_can_update_user_npk_and_position()
    {
        $user = User::factory()->create([
            'npk' => '87654321',
            'position' => 'Old Position',
        ]);

        $response = $this->actingAs($this->admin)->put(route('she.users.update', $user->id), [
            'name' => 'Updated User',
            'email' => $user->email,
            'npk' => '11112222',
            'position' => 'New Position',
            'role' => 'karyawan',
        ]);

        $response->assertRedirect(route('she.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'npk' => '11112222',
            'position' => 'New Position',
        ]);
    }

    public function test_index_page_shows_npk_and_position()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'npk' => 'NPK001',
            'position' => 'Manager',
        ]);

        $response = $this->actingAs($this->admin)->get(route('she.users.index'));

        $response->assertStatus(200);
        $response->assertSee('NPK001');
        $response->assertSee('John Doe');
        $response->assertSee('Manager');
    }
}
