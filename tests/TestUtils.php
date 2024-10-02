<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;

class TestUtils
{
    private KernelInterface $kernel;
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function launchFixtures($groups): void
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $command = [
          'command' => 'doctrine:mongodb:fixtures:load',
          '--no-interaction' => true,
          '--env' => 'test',
          '--quiet' => true
        ];

        if (count($groups) > 1) {
            $command['--group'] = $groups;
        }

        $input = new ArrayInput($command);

        $application->run($input);
    }
}
