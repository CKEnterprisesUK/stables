<?php

namespace Tests\Feature\Sponsor;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\StripeServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class BillingControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createSponsor(array $attributes = []): User
    {
        return User::factory()->create(array_merge(['role' => UserRole::Sponsor], $attributes));
    }

    public function test_billing_requires_authentication(): void
    {
        $response = $this->get(route('sponsor.billing'));

        $response->assertRedirect(route('login'));
    }

    public function test_billing_redirects_to_stripe_portal_when_user_has_stripe_id(): void
    {
        $sponsor = $this->createSponsor(['stripe_id' => 'cus_test_123']);

        $portalUrl = 'https://billing.stripe.com/p/session/test_abc123';

        $mock = Mockery::mock(StripeServiceInterface::class);
        $mock->shouldReceive('getPortalUrl')
            ->once()
            ->with(Mockery::on(fn ($user) => $user->id === $sponsor->id))
            ->andReturn($portalUrl);

        $this->app->instance(StripeServiceInterface::class, $mock);

        $response = $this->actingAs($sponsor)->get(route('sponsor.billing'));

        $response->assertRedirect($portalUrl);
    }

    public function test_billing_redirects_back_with_error_when_no_stripe_id(): void
    {
        $sponsor = $this->createSponsor(['stripe_id' => null]);

        $response = $this->actingAs($sponsor)
            ->from(route('sponsor.dashboard'))
            ->get(route('sponsor.billing'));

        $response->assertRedirect(route('sponsor.dashboard'));
        $response->assertSessionHas('error', 'No billing account found. Please create a sponsorship first.');
    }
}
