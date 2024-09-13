<?php

namespace Illuminate\Tests\Database;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Database\Events\MigrationsInstalling;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Foundation\Application;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class DatabaseMigrationInstallCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testFireCallsRepositoryToInstall()
    {
        $command = new InstallCommand($repo = m::mock(MigrationRepositoryInterface::class), $dispatcher = m::mock(Dispatcher::class));
        $command->setLaravel(new Application);
        $repo->shouldReceive('setSource')->once()->with('foo');
        $dispatcher->shouldReceive('dispatch')->once()->withArgs(function (MigrationsInstalling $event) {
            $this->assertInstanceOf(MigrationRepositoryInterface::class, $event->repository);

            return true;
        });
        $repo->shouldReceive('createRepository')->once();

        $this->runCommand($command, ['--database' => 'foo']);
    }

    protected function runCommand($command, $options = [])
    {
        return $command->run(new ArrayInput($options), new NullOutput);
    }
}
