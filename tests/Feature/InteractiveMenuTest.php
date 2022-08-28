<?php

namespace Tests\Feature;

use App\Models\FlashCard;
use App\Models\User;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class InteractiveMenuTest extends TestCase
{
    private array $menuOptions = [
        'Create a flashcard',
        'List all flashcards',
        'Practice',
        'Stats',
        'Reset',
        'Exit',
    ];

    public function testChooseCreate()
    {
        $user = User::factory()->create();
        $this->artisan('flashcard:interactive', ['--user' => $user->username])
            ->expectsChoice('Please choose an option', 'Create a flashcard', $this->menuOptions)
            ->expectsQuestion('Question 1', '')
            ->expectsConfirmation('0 item entered. Do you want to exit?', 'yes')
            ->expectsChoice('Please choose an option', 'Exit', $this->menuOptions)
            ->assertExitCode(0)
        ;
    }

    public function testChooseListFlashcards()
    {
        $user  = User::factory()->create();
        $cards = FlashCard::factory()->count(5)->create(['user_id' => $user->id]);

        $this->artisan('flashcard:interactive', ['--user' => $user->username])
            ->expectsChoice('Please choose an option', 'List all flashcards', $this->menuOptions)
            ->expectsTable(['Question', 'Answer'], $cards->map(function ($model) {
                return $model->only(['question', 'answer']);
            }), 'box-double')
            ->expectsChoice('Please choose an option', 'Exit', $this->menuOptions)
            ->assertExitCode(0)
        ;
    }

    public function testChooseReset()
    {
        $user = User::factory()->create();
        $this->artisan('flashcard:interactive', ['--user' => $user->username])
            ->expectsChoice('Please choose an option', 'Reset', $this->menuOptions)
            ->expectsConfirmation('Are you sure you want to clear all practices?', 'no')
            ->expectsChoice('Please choose an option', 'Exit', $this->menuOptions)
            ->assertExitCode(0)
        ;
    }

    public function testChooseExit()
    {
        $user = User::factory()->create();
        $this->artisan('flashcard:interactive', ['--user' => $user->username])
            ->expectsChoice('Please choose an option', 'Exit', $this->menuOptions)
            ->assertExitCode(0)
        ;
    }
}
