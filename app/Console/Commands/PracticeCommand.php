<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\PracticeService;
use Illuminate\Database\Eloquent\Collection;

class PracticeCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashcard:practice 
                            {--u|user=}
                            {--i|interactive}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Practice all flashcards';

    /**
     * A service to manage practices.
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

        $this->alert('Practice');

        $repeate = true;
        while ($repeate) {
            $completion   = $this->practiceService->completionPercentage($user);
            $allPractices = $this->practiceService->getPractices($user);
            $this->showProgress($allPractices, $completion);

            if ($completion < 100) {
                $this->info("\nPlease answer the questions\n");
                $this->startPractice($allPractices, $user);
            } else {
                $this->info("\nTo start a new practice please use RESET option!\n");

                break;
            }

            $repeate = $this->confirm("\nDo you want to repeate the practice?\n");
        }

        return $this->homeScreen();
    }

    /**
     * Show a progress table of the user practices.
     */
    private function showProgress(Collection $allPractices, int $completion): Collection
    {
        $this->table(
            ['Question', 'Status'],
            $this->practiceService->flattenPracticeCollection($allPractices),
            'box-double'
        );

        $this->comment(sprintf('Completion: %d%s', $completion, '%'));

        return $allPractices;
    }

    /**
     * Practice NOT ANSWERD and INCORRECT flashcards.
     */
    private function startPractice(Collection $allPractices, User $user)
    {
        $this->withProgressBar($this->filterQuestions($allPractices), function ($card) use ($user) {
            if ($answer = $this->ask("\n" . $card->question)) {
                $this->practiceService->addPractice($user, $card, $answer);
                match ($answer == $card->answer) {
                    true  => $this->warn('CORRECT'),
                    false => $this->error('INCORRECT')
                };
                $this->newLine(2);
            }
        });
    }

    /**
     * Filter questions and remove correctly answered ones.
     */
    private function filterQuestions(Collection $questions): Collection
    {
        return $questions->filter(function ($model) {
            foreach ($model->practices as $item) {
                if ($item->pivot->result) {
                    return false;
                }
            }

            return true;
        });
    }
}
