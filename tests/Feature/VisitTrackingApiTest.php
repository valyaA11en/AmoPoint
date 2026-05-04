<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class VisitTrackingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_visit(): void
    {
        $payload = [
            'visitor_id' => '550e8400-e29b-41d4-a716-446655440000',
            'device_type' => 'desktop',
            'user_agent' => 'Feature test user agent',
            'page_url' => 'http://127.0.0.1:8000/test-filter.html',
            'referrer' => null,
            'language' => 'ru-RU',
            'timezone' => 'Europe/Moscow',
            'screen' => '1920x1080',
        ];

        $this->postJson('/api/visits', $payload)
            ->assertCreated()
            ->assertJsonPath('status', 'ok');

        $this->assertDatabaseHas('visits', [
            'visitor_id' => '550e8400-e29b-41d4-a716-446655440000',
            'device_type' => 'desktop',
            'user_agent' => 'Feature test user agent',
            'page_url' => 'http://127.0.0.1:8000/test-filter.html',
            'language' => 'ru-RU',
            'timezone' => 'Europe/Moscow',
            'screen' => '1920x1080',
        ]);
    }

    public function test_it_validates_required_fields(): void
    {
        $this->postJson('/api/visits', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'visitor_id',
                'device_type',
                'page_url',
            ]);
    }
}