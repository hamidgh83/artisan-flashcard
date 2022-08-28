<?php

namespace App\Console\Commands;

class MenuCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:interactive {--u|user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show interactive flashcard menu';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user           = $this->user();
        $defaultOptions = [
            '-i'     => true,
            '--user' => $user->username,
        ];

        if (!match ($this->readChoice()) {
            1       => $this->call('flashcard:create', $defaultOptions),
            2       => $this->call('flashcard:list', $defaultOptions),
            3       => $this->call('flashcard:practice', $defaultOptions),
            4       => $this->call('flashcard:stats', $defaultOptions),
            5       => $this->call('flashcard:reset', $defaultOptions),
            6       => $this->info("Goodbye ^_^\n"),
            default => false
        }) {
            return $this->exit();
        }

        $this->handle();
    }

    private function readChoice()
    {
        $options = [
            1 => 'Create a flashcard',
            2 => 'List all flashcards',
            3 => 'Practice',
            4 => 'Stats',
            5 => 'Reset',
            6 => 'Exit',
        ];

        return array_search(
            $this->choice('Please choose an option', $options),
            $options
        );
    }
}
