<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class VisitStatisticsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/visits')
            ->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_statistics_dashboard(): void
    {
        $user = User::factory()->create();

        Visit::query()->create([
            'visitor_id' => '550e8400-e29b-41d4-a716-446655440000',
            'ip_address' => '127.0.0.1',
            'city' => 'Unknown',
            'device_type' => 'desktop',
            'user_agent' => 'Feature test user agent',
            'page_url' => 'http://127.0.0.1:8000/test-filter.html',
            'referrer' => null,
            'language' => 'ru-RU',
            'timezone' => 'Europe/Moscow',
            'screen' => '1920x1080',
            'visited_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/visits')
            ->assertOk()
            ->assertSee('Visit Statistics')
            ->assertSee('Всего посещений')
            ->assertSee('Уникальных посетителей');
    }
}