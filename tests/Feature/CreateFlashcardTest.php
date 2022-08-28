<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class CreateFlashcardTest extends TestCase
{
    public function testCreateFlashcardWithUsernameSuccess()
    {
        $user     = User::factory()->create();
        $question = $this->faker->sentence();
        $answer   = $this->faker->word();
        $this->artisan('flashcard:create', ['--user' => $user->username])
            ->expectsQuestion('Question 1', $question)
            ->expectsQuestion('Answer', $answer)
            ->expectsQuestion('Question 2', '')
            ->expectsConfirmation('1 item entered. Do you want to save?', 'yes')
            ->assertSuccessful()
        ;

        $this->assertDatabaseHas('flash_cards', [
            'question' => $question,
            'answer'   => $answer,
        ])->assertDatabaseCount('flash_cards', 1);
    }

    public function testCreateFlashcardWithoutUsername()
    {
        $question = $this->faker->sentence();
        $answer   = $this->faker->word();
        $this->artisan('flashcard:create')
            ->expectsQuestion('Username', $this->faker->userName())
            ->expectsConfirmation('User was not found! Do you want to create a new user?', 'no')
            ->expectsQuestion('Username', '')
            ->expectsQuestion('Question 1', $question)
            ->expectsQuestion('Answer', $answer)
            ->expectsQuestion('Question 2', '')
            ->expectsConfirmation('1 item entered. Do you want to save?', 'yes')
            ->assertSuccessful()
        ;

        $this->assertDatabaseHas('flash_cards', [
            'question' => $question,
            'answer'   => $answer,
        ])->assertDatabaseCount('flash_cards', 1);
    }

    public function testCreateFlashcardWithInvalidUsername()
    {
        $question = $this->faker->sentence();
        $answer   = $this->faker->word();
        $this->artisan('flashcard:create', ['--user' => $this->faker->userName()])
            ->expectsOutput('Invalid user')
            ->expectsQuestion('Username', '')
            ->expectsQuestion('Question 1', $question)
            ->expectsQuestion('Answer', $answer)
            ->expectsQuestion('Question 2', '')
            ->expectsConfirmation('1 item entered. Do you want to save?', 'yes')
            ->assertSuccessful()
        ;

        $this->assertDatabaseHas('flash_cards', [
            'question' => $question,
            'answer'   => $answer,
        ])->assertDatabaseCount('flash_cards', 1);
    }

    public function testCreateFlashcardNotSaved()
    {
        $this->artisan('flashcard:create')
            ->expectsQuestion('Username', '')
            ->expectsQuestion('Question 1', $this->faker->sentence())
            ->expectsQuestion('Answer', $this->faker->word())
            ->expectsQuestion('Question 2', '')
            ->expectsConfirmation('1 item entered. Do you want to save?', 'no')
            ->assertSuccessful()
        ;

        $this->assertDatabaseCount('flash_cards', 0);
    }

    public function testCreateFlashcardSaved()
    {
        $question = $this->faker->sentence();
        $answer   = $this->faker->word();
        $this->artisan('flashcard:create')
            ->expectsQuestion('Username', '')
            ->expectsQuestion('Question 1', $question)
            ->expectsQuestion('Answer', $answer)
            ->expectsQuestion('Question 2', '')
            ->expectsConfirmation('1 item entered. Do you want to save?', 'yes')
            ->assertSuccessful()
        ;

        $this->assertDatabaseHas('flash_cards', [
            'question' => $question,
            'answer'   => $answer,
        ])->assertDatabaseCount('flash_cards', 1);
    }
}
