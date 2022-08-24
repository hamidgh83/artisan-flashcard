<?php

namespace App\Console\Commands;

use App\Models\FlashCard;
use App\Services\FlashCardService;
use Illuminate\Database\Eloquent\Collection;

class CreateCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:create {--m|menu=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create one or more flashcards';

    /**
     * The service to manage flash cards.
     */
    protected FlashCardService $flashCardService;

    public function __construct(FlashCardService $flashCardService)
    {
        parent::__construct();

        $this->flashCardService = $flashCardService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->alert('Create your flash cards');
        $this->info('To save your changes leave the question line empty and press ENTER.');

        $index = 1;
        $items = null;
        while (true) {
            if ($question = $this->ask(sprintf('Question %d', $index))) {
                if ($answer = $this->ask('Answer')) {
                    $model = new FlashCard(['question' => $question, 'answer' => $answer]);
                    $items = $this->flashCardService->add($model);
                    ++$index;

                    continue;
                }
            }

            if ($this->confirmSave($items)) {
                if ($items instanceof Collection) {
                    $this->flashCardService->store($items);
                    $total = $items->count();
                    $this->info(sprintf('%d flash card%s saved successfully!', $total, $total > 1 ? 's' : ''));
                }
            } elseif ($items?->count() < 1) {
                continue;
            }

            break;
        }

        return $this->homeScreen();
    }

    /**
     * Confirm save flash cards and exit.
     */
    private function confirmSave(?Collection $items): bool
    {
        $total = $items?->count();

        return $this->confirm(
            sprintf(
                '%d %s entered. Do you want to %s?',
                $total,
                $total < 2 ? 'item' : 'items',
                $total > 0 ? 'save' : 'exit'
            ),
            true
        );
    }
}
