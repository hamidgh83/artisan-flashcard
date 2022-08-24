<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class AbstractCommand extends Command
{
    protected $home = 'flashcard:interactive';

    /**
     * Show home screen to choose from menu.
     */
    public function homeScreen()
    {
        if (true === $this->option('menu')) {
            return $this->call($this->home);
        }

        return 0;
    }

    /**
     * Exit the app.
     */
    public function exit()
    {
        return 0;
    }

    /**
     * Get back to home screen.
     */
    public function continue()
    {
        return $this->homeScreen();
    }
}
