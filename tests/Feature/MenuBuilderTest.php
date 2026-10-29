<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SystemMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class MenuBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'menu.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu.create', 'guard_name' => 'web']);

        $this->admin = User::factory()->create(['user_type' => 'admin']);
        $this->admin->givePermissionTo(['menu.view', 'menu.create']);
    }

    public function test_menu_builder_index_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.menus.index'));
        $response->assertStatus(200);
    }
}
