<?php

namespace Tests\Feature\Sponsor;

use App\Enums\SponsorshipStatus;
use App\Enums\UserRole;
use App\Models\Horse;
use App\Models\HorseUpdate;
use App\Models\Sponsorship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createSponsor(): User
    {
        return User::factory()->create(['role' => UserRole::Sponsor]);
    }

    private function createHorseWithSponsorship(User $sponsor, string $status = 'active', ?string $childName = null): array
    {
        $horse = Horse::factory()->create();
        $sponsorship = Sponsorship::create([
            'user_id' => $sponsor->id,
            'horse_id' => $horse->id,
            'stripe_subscription_id' => 'sub_test_' . uniqid(),
            'monthly_amount' => 1500,
            'child_name' => $childName,
            'status' => $status,
            'ends_at' => $status === 'cancelled' ? now() : null,
        ]);

        return [$horse, $sponsorship];
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('sponsor.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_displays_sponsor_sponsorships(): void
    {
        $sponsor = $this->createSponsor();
        [$horse, $sponsorship] = $this->createHorseWithSponsorship($sponsor);

        $response = $this->actingAs($sponsor)->get(route('sponsor.dashboard'));

        $response->assertOk();
        $response->assertSee($horse->name);
    }

    public function test_dashboard_displays_active_and_cancelled_sponsorships(): void
    {
        $sponsor = $this->createSponsor();
        [$activeHorse] = $this->createHorseWithSponsorship($sponsor, 'active');
        [$cancelledHorse] = $this->createHorseWithSponsorship($sponsor, 'cancelled');

        $response = $this->actingAs($sponsor)->get(route('sponsor.dashboard'));

        $response->assertOk();
        $response->assertSee($activeHorse->name);
        $response->assertSee($cancelledHorse->name);
        $response->assertSee('Active');
        $response->assertSee('Cancelled');
    }

    public function test_dashboard_shows_updates_for_active_sponsorships(): void
    {
        $sponsor = $this->createSponsor();
        [$horse] = $this->createHorseWithSponsorship($sponsor, 'active');

        $update = HorseUpdate::create([
            'horse_id' => $horse->id,
            'title' => 'Spring gallop in the meadow',
            'body' => 'The horse had a great time today!',
        ]);

        $response = $this->actingAs($sponsor)->get(route('sponsor.dashboard'));

        $response->assertOk();
        $response->assertSee('Spring gallop in the meadow');
        $response->assertSee('The horse had a great time today!');
    }

    public function test_dashboard_hides_updates_for_cancelled_sponsorships(): void
    {
        $sponsor = $this->createSponsor();
        [$horse] = $this->createHorseWithSponsorship($sponsor, 'cancelled');

        $update = HorseUpdate::create([
            'horse_id' => $horse->id,
            'title' => 'Update should be hidden',
            'body' => 'This update should not be visible.',
        ]);

        $response = $this->actingAs($sponsor)->get(route('sponsor.dashboard'));

        $response->assertOk();
        $response->assertDontSee('Update should be hidden');
        $response->assertDontSee('This update should not be visible.');
    }

    public function test_dashboard_shows_updates_in_reverse_chronological_order(): void
    {
        $sponsor = $this->createSponsor();
        [$horse] = $this->createHorseWithSponsorship($sponsor, 'active');

        $olderUpdate = HorseUpdate::create([
            'horse_id' => $horse->id,
            'title' => 'Older update',
            'body' => 'This happened first.',
        ]);
        $olderUpdate->created_at = now()->subDays(5);
        $olderUpdate->save();

        $newerUpdate = HorseUpdate::create([
            'horse_id' => $horse->id,
            'title' => 'Newer update',
            'body' => 'This happened more recently.',
        ]);
        $newerUpdate->created_at = now()->subDay();
        $newerUpdate->save();

        $response = $this->actingAs($sponsor)->get(route('sponsor.dashboard'));

        $response->assertOk();
        $response->assertSeeInOrder(['Newer update', 'Older update']);
    }

    public function test_dashboard_does_not_show_other_sponsors_data(): void
    {
        $sponsor = $this->createSponsor();
        $otherSponsor = $this->createSponsor();
        [$otherHorse] = $this->createHorseWithSponsorship($otherSponsor, 'active');

        $response = $this->actingAs($sponsor)->get(route('sponsor.dashboard'));

        $response->assertOk();
        $response->assertDontSee($otherHorse->name);
    }

    public function test_dashboard_empty_state_when_no_sponsorships(): void
    {
        $sponsor = $this->createSponsor();

        $response = $this->actingAs($sponsor)->get(route('sponsor.dashboard'));

        $response->assertOk();
        $response->assertSee("You don't have any sponsorships yet.", false);
        $response->assertSee('Browse Horses');
    }

    public function test_dashboard_shows_child_name_for_child_sponsorships(): void
    {
        $sponsor = $this->createSponsor();
        [$horse] = $this->createHorseWithSponsorship($sponsor, 'active', 'Little Emma');

        $response = $this->actingAs($sponsor)->get(route('sponsor.dashboard'));

        $response->assertOk();
        $response->assertSee('Little Emma');
    }
}
