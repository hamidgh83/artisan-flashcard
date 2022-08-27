<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(string $username): User
    {
        return $this->repository->create(['username' => $username]);
    }

    public function get(?string $username = null): ?User
    {
        if (!trim($username)) {
            $username = 'u_' . date('ymdHis');

            return $this->create($username);
        }

        return $this->repository->findOneBy(['username' => $username]);
    }
}
