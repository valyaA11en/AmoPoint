<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Jokes\FetchAndStoreJokeAction;
use Illuminate\Console\Command;
use Throwable;

final class FetchJokeCommand extends Command
{
    protected $signature = 'jokes:fetch';

    protected $description = 'Fetch random joke from external API and save it to database';

    public function handle(FetchAndStoreJokeAction $action): int
    {
        try {
            $joke = $action->execute();

            $this->info(
                $joke->wasRecentlyCreated
                ? 'Joke saved successfully.'
                : 'Joke already exists and was updated.'
            );

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);

            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}