<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Joke;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchJokeCommand extends Command
{
    protected $signature = 'jokes:fetch';

    protected $description = 'Fetch random joke from external API and save it to database';

    public function handle(): int
    {
        try {
            $response = Http::acceptJson()
                ->timeout(10)
                ->retry(3, 200)
                ->get('https://official-joke-api.appspot.com/random_joke');

            if ($response->failed()) {
                $this->error('API request failed. Status: ' . $response->status());

                return self::FAILURE;
            }

            $data = $response->json();

            if (
                empty($data['setup']) ||
                empty($data['punchline'])
            ) {
                $this->error('API returned invalid data.');

                return self::FAILURE;
            }

            $joke = Joke::query()->updateOrCreate(
                [
                    'external_id' => isset($data['id']) ? (string) $data['id'] : null,
                ],
                [
                    'setup' => $data['setup'],
                    'punchline' => $data['punchline'],
                ]
            );

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