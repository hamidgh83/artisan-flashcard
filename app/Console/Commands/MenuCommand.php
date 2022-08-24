<?php

namespace App\Console\Commands;

class MenuCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:interactive';

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
        $options = [
            1 => 'Create a flashcard',
            2 => 'List all flashcards',
            3 => 'Practice',
            4 => 'Exit',
        ];

        $choice = array_search(
            $this->choice('Please choose an option', $options),
            $options
        );

        if (!match ($choice) {
            1 => $this->call('flashcard:create', ['--menu' => true]),
            2 => $this->call('flashcard:list', ['--menu' => true]),
            3 => $this->call('flashcard:practice', ['--menu' => true]),
            4 => $this->info("Goodbye ^_^\n"),
        }) {
            return $this->exit();
        }

        $this->handle();
    }
}
