<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Business;
use App\Livewire\Settings\BusinessManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BusinessManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_business_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(BusinessManager::class)
            ->set('name', 'Acme Corp')
            ->set('email', 'billing@acme.com')
            ->set('phone', '123456789')
            ->set('address', '123 Acme St')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('businesses', [
            'user_id' => $user->id,
            'name' => 'Acme Corp',
            'email' => 'billing@acme.com',
            'phone' => '123456789',
            'address' => '123 Acme St',
        ]);
    }

    public function test_can_edit_and_update_business_profile(): void
    {
        $user = User::factory()->create();
        $business = $user->businesses()->create([
            'name' => 'Original Name',
            'email' => 'original@test.com',
            'phone' => '111222',
            'address' => 'Original Road',
        ]);

        $this->actingAs($user);

        Livewire::test(BusinessManager::class)
            ->call('openEdit', $business->id)
            ->assertSet('name', 'Original Name')
            ->assertSet('editing', true)
            ->assertSet('editingId', $business->id)
            ->set('name', 'Updated Name')
            ->set('email', 'updated@test.com')
            ->call('save')
            ->assertHasNoErrors();

        $business->refresh();
        $this->assertSame('Updated Name', $business->name);
        $this->assertSame('updated@test.com', $business->email);
        $this->assertSame('111222', $business->phone); // Unchanged
    }

    public function test_can_delete_business_profile(): void
    {
        $user = User::factory()->create();
        $business = $user->businesses()->create([
            'name' => 'ToDelete Corp',
        ]);

        $this->actingAs($user);

        Livewire::test(BusinessManager::class)
            ->call('deleteBusiness', $business->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('businesses', [
            'id' => $business->id,
        ]);
    }
}
