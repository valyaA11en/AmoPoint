<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class FetchJokeCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fetches_and_stores_joke(): void
    {
        Http::fake([
            'official-joke-api.appspot.com/*' => Http::response([
                'id' => 123,
                'type' => 'general',
                'setup' => 'Why did the developer go broke?',
                'punchline' => 'Because he used up all his cache.',
            ]),
        ]);

        $this->artisan('jokes:fetch')
            ->assertSuccessful();

        $this->assertDatabaseHas('jokes', [
            'external_id' => '123',
            'setup' => 'Why did the developer go broke?',
            'punchline' => 'Because he used up all his cache.',
        ]);
    }
}