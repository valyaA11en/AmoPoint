<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Joke;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class JokesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_jokes_collection(): void
    {
        Joke::query()->create([
            'external_id' => '456',
            'setup' => 'Test setup?',
            'punchline' => 'Test punchline.',
        ]);

        $this->getJson('/api/jokes')
            ->assertOk()
            ->assertJsonPath('data.0.external_id', '456')
            ->assertJsonPath('data.0.setup', 'Test setup?')
            ->assertJsonPath('data.0.punchline', 'Test punchline.');
    }
}