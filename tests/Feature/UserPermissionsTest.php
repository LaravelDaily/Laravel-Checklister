<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserPermissionsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function test_can_register_and_see_welcome_page()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $website = $this->faker->url;
        $password = $this->faker->password(8);

        $response = $this->post('/register', [
            'name' => $name,
            'email' => $email,
            'website' => $website,
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertRedirect('welcome');

        $user = User::where([
            'name' => $name,
            'email' => $email,
            'website' => $website,
        ])->first();
        $this->assertNotNull($user);

        $response = $this->actingAs($user)->get('welcome');
        $response->assertStatus(200);
    }

    public function test_cannot_access_admin_menu_items()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $menu = (new MenuService())->get_menu();
        $this->assertCount(0, $menu['admin_menu']);

        $response = $this->get('admin/checklist_groups');
        $response->assertStatus(403);

        $response = $this->post('admin/checklist_groups', [
            'name' => 'Checklist group'
        ]);
        $response->assertStatus(403);

        $response = $this->get('admin/pages/1/edit');
        $response->assertStatus(403);

        $response = $this->get('admin/users');
        $response->assertStatus(403);
    }
}
