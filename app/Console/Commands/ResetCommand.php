<?php

namespace App\Console\Commands;

use App\Services\PracticeService;

class ResetCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:reset
                            {--u|user=}
                            {--i|interactive}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset user practices';

    /**
     * The service to manage practices.
     */
    protected PracticeService $practiceService;

    public function __construct(PracticeService $practiceService)
    {
        parent::__construct();

        $this->practiceService = $practiceService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = $this->user();

        if ($this->confirm('Are you sure you want to clear all practices?')) {
            $this->practiceService->reset($user);
            $this->warn("\nPractices cleared successfuly!\n");
        }

        return $this->homeScreen();
    }
}
