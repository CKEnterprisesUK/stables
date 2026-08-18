<?php

namespace Tests\Feature;

use App\Enums\SponsorshipStatus;
use App\Enums\UserRole;
use App\Models\Horse;
use App\Models\Sponsorship;
use App\Models\User;
use App\Services\StripeServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Subscription;
use Mockery;
use Tests\TestCase;

class SignupControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_form_is_displayed_for_a_horse(): void
    {
        $horse = Horse::factory()->create(['name' => 'Thunderbolt']);

        $response = $this->get(route('signup.create', $horse));

        $response->assertStatus(200);
        $response->assertSee('Thunderbolt');
    }

    public function test_signup_creates_user_and_sponsorship(): void
    {
        $horse = Horse::factory()->create();

        // Create a real Subscription instance with the stripe_id set
        $mockSubscription = new Subscription();
        $mockSubscription->stripe_id = 'sub_test_123';

        $mockStripeService = Mockery::mock(StripeServiceInterface::class);
        $mockStripeService->shouldReceive('createSubscription')
            ->once()
            ->withArgs(function ($user, $amountInCents, $paymentMethodId) {
                return $user instanceof User
                    && $amountInCents === 500
                    && $paymentMethodId === 'pm_test_payment_method';
            })
            ->andReturn($mockSubscription);

        $this->app->instance(StripeServiceInterface::class, $mockStripeService);

        $response = $this->post(route('signup.store', $horse), [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'monthly_amount' => 5,
            'payment_method' => 'pm_test_payment_method',
        ]);

        $response->assertRedirect(route('sponsor.dashboard'));

        // Assert user was created
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'name' => 'Jane Smith',
            'role' => UserRole::Sponsor->value,
        ]);

        // Assert sponsorship was created
        $this->assertDatabaseHas('sponsorships', [
            'horse_id' => $horse->id,
            'stripe_subscription_id' => 'sub_test_123',
            'monthly_amount' => 500,
            'child_name' => null,
            'status' => SponsorshipStatus::Active->value,
        ]);

        // Assert user is authenticated
        $this->assertAuthenticated();
    }

    public function test_signup_with_child_name_stores_child_sponsorship(): void
    {
        $horse = Horse::factory()->create();

        // Create a real Subscription instance with the stripe_id set
        $mockSubscription = new Subscription();
        $mockSubscription->stripe_id = 'sub_test_456';

        $mockStripeService = Mockery::mock(StripeServiceInterface::class);
        $mockStripeService->shouldReceive('createSubscription')
            ->once()
            ->andReturn($mockSubscription);

        $this->app->instance(StripeServiceInterface::class, $mockStripeService);

        $response = $this->post(route('signup.store', $horse), [
            'name' => 'Parent Name',
            'email' => 'parent@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'monthly_amount' => 10,
            'child_name' => 'Little Timmy',
            'payment_method' => 'pm_test_child_payment',
        ]);

        $response->assertRedirect(route('sponsor.dashboard'));

        // Assert child name was stored
        $this->assertDatabaseHas('sponsorships', [
            'horse_id' => $horse->id,
            'child_name' => 'Little Timmy',
            'monthly_amount' => 1000,
            'status' => SponsorshipStatus::Active->value,
        ]);
    }

    public function test_signup_validates_required_fields(): void
    {
        $horse = Horse::factory()->create();

        $response = $this->post(route('signup.store', $horse), []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'monthly_amount', 'payment_method']);
    }

    public function test_signup_validates_email_uniqueness(): void
    {
        $horse = Horse::factory()->create();
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post(route('signup.store', $horse), [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'monthly_amount' => 5,
            'payment_method' => 'pm_test',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_signup_validates_minimum_amount(): void
    {
        $horse = Horse::factory()->create();

        $response = $this->post(route('signup.store', $horse), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'monthly_amount' => 0,
            'payment_method' => 'pm_test',
        ]);

        $response->assertSessionHasErrors(['monthly_amount']);
    }

    public function test_signup_validates_password_confirmation(): void
    {
        $horse = Horse::factory()->create();

        $response = $this->post(route('signup.store', $horse), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
            'monthly_amount' => 5,
            'payment_method' => 'pm_test',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
