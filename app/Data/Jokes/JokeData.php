<?php

declare(strict_types=1);

namespace App\Data\Jokes;

final readonly class JokeData
{
    public function __construct(
        public string $externalId,
        public string $setup,
        public string $punchline,
    ) {
    }
}