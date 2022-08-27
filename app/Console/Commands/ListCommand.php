<?php

namespace App\Console\Commands;

use App\Services\FlashCardService;

class ListCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:list 
                            {--u|user=}
                            {--i|interactive}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List already created fashcards';

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
        $user = $this->user();
        $this->table(
            ['Question', 'Answer'],
            $this->flashCardService->getAll($user, ['question', 'answer']),
            'box-double'
        );

        return $this->homeScreen();
    }
}
