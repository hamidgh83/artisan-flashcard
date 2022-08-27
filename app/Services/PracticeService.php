<?php

namespace App\Services;

use App\Repositories\PracticeRepository;

class PracticeService
{
    protected PracticeRepository $repository;

    public function __construct(PracticeRepository $repository)
    {
        $this->repository = $repository;
    }
}
