<?php

namespace Illuminate\Database\Events;

use Illuminate\Contracts\Database\Events\MigrationEvent;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;

class MigrationsInstalling implements MigrationEvent
{
    public $repository;

    public function __construct(MigrationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}
