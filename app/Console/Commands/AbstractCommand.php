<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Console\Command;

abstract class AbstractCommand extends Command
{
    protected UserService $userService;

    protected $home = 'flashcard:interactive';

    public function __construct()
    {
        parent::__construct();

        $this->userService = app()->make(UserService::class);
    }

    /**
     * Show home screen to choose from menu.
     */
    public function homeScreen()
    {
        if (true === $this->option('interactive')) {
            return $this->call($this->home, [
                '--user' => $this->getOption('user'),
            ]);
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

    /**
     * Get options with default value.
     *
     * @param null|string            $key
     * @param null|array|bool|string $default Default value
     *
     * @return null|array|bool|string
     */
    protected function getOption($key, $default = null)
    {
        return $this->hasOption($key) ? $this->option($key) : $default;
    }

    /**
     * Get User model.
     */
    protected function user(): User
    {
        if ($username = $this->getOption('user')) {
            $user = $this->userService->get($username);
            if ($user instanceof User) {
                return $user;
            }
            $this->error('Invalid user');
        }

        do {
            $username = $this->ask('Username');
            $user     = $this->userService->get($username);

            if (!$user instanceof User) {
                if ($this->confirm('User was not found! Do you want to login as a new user?', true)) {
                    $user = $this->userService->get();
                }
            }
        } while (!$user);

        $this->warn(sprintf('You are logged in as "%s"', $user->username));

        return $user;
    }
}
