<?php

declare(strict_types=1);

namespace App\Services\Jokes;

use App\Data\Jokes\JokeData;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class JokeApiClient
{
    private const API_URL = 'https://official-joke-api.appspot.com/random_joke';

    public function fetchRandom(): JokeData
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->retry(3, 200)
            ->get(self::API_URL);

        if ($response->failed()) {
            throw new RuntimeException(
                'Joke API request failed. Status: ' . $response->status()
            );
        }

        $data = $response->json();

        if (
            !is_array($data) ||
            empty($data['setup']) ||
            empty($data['punchline'])
        ) {
            throw new RuntimeException('Joke API returned invalid data.');
        }

        return new JokeData(
            externalId: isset($data['id'])
            ? (string) $data['id']
            : hash('sha256', $data['setup'] . '|' . $data['punchline']),
            setup: (string) $data['setup'],
            punchline: (string) $data['punchline'],
        );
    }
}