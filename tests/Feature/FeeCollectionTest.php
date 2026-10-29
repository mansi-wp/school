<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\FeePlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class FeeCollectionTest extends TestCase
{
    use RefreshDatabase;

    protected User $accountant;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'fee.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'fee.collect', 'guard_name' => 'web']);

        $this->accountant = User::factory()->create(['user_type' => 'accountant']);
        $this->accountant->givePermissionTo(['fee.view', 'fee.collect']);
    }

    public function test_fee_ledger_screen_accessible(): void
    {
        $response = $this->actingAs($this->accountant)->get(route('admin.fees.index'));
        $response->assertStatus(200);
    }
}
