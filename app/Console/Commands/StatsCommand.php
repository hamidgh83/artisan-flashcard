<?php

namespace App\Console\Commands;

use App\Services\PracticeService;

class StatsCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:stats
                            {--u|user=}
                            {--i|interactive}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display a stat of your practices.';

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
        return $this->homeScreen();
    }
}
