<?php

namespace Tests\Feature;

use App\Models\User;
use App\Livewire\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response
            ->assertOk()
            ->assertSeeLivewire(Profile::class);
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(Profile::class)
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('default_currency', 'EUR')
            ->call('updateProfile');

        $component
            ->assertHasNoErrors()
            ->assertStatus(200);

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertSame('EUR', $user->default_currency);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $component = Livewire::test(Profile::class)
            ->set('name', 'Test User')
            ->set('email', $user->email)
            ->call('updateProfile');

        $component->assertHasNoErrors();

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_security_pin_can_be_set_and_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // Initially no PIN
        $this->assertEmpty($user->security_pin);

        // Set PIN
        $component = Livewire::test(Profile::class)
            ->set('pin', '1234')
            ->set('pin_confirmation', '1234')
            ->call('updatePin');

        $component
            ->assertHasNoErrors()
            ->assertStatus(200);

        $user->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('1234', $user->security_pin));

        // Update PIN (requires old PIN)
        $component = Livewire::test(Profile::class)
            ->set('old_pin', '1234')
            ->set('pin', '5678')
            ->set('pin_confirmation', '5678')
            ->call('updatePin');

        $component
            ->assertHasNoErrors()
            ->assertStatus(200);

        $user->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('5678', $user->security_pin));
    }
}
