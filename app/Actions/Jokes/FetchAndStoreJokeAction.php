<?php

declare(strict_types=1);

namespace App\Actions\Jokes;

use App\Models\Joke;
use App\Services\Jokes\JokeApiClient;

final class FetchAndStoreJokeAction
{
    public function __construct(
        private readonly JokeApiClient $jokeApiClient,
    ) {
    }

    public function execute(): Joke
    {
        $jokeData = $this->jokeApiClient->fetchRandom();

        return Joke::query()->updateOrCreate(
            [
                'external_id' => $jokeData->externalId,
            ],
            [
                'setup' => $jokeData->setup,
                'punchline' => $jokeData->punchline,
            ]
        );
    }
}