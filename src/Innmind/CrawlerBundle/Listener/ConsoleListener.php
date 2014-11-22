<?php

namespace Innmind\CrawlerBundle\Listener;

use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Process\Process;
use Psr\Log\LoggerInterface;

class ConsoleListener
{
    protected $rootDir;
    protected $env;
    protected $logger;

    /**
     * Set the application directory
     *
     * @param string $dir
     */

    public function setRootDir($dir)
    {
        $this->rootDir = (string) $dir;
    }

    /**
     * Set the current environment
     *
     * @param string $env
     */

    public function setEnv($env)
    {
        $this->env = (string) $env;
    }

    /**
     * Set the logger
     *
     * @param LoggerInterface $logger
     */

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onConsoleTerminate(ConsoleTerminateEvent $event)
    {
        $command = $event->getCommand();

        if ($command->getName() === 'rabbitmq:consumer') {
            $process = new Process(sprintf(
                'cd %s && ./console rabbitmq:consumer resource -m 50 -e %s',
                $this->rootDir,
                $this->env
            ));
            $process->start();
            $this->logger->info('Relaunching a new consumer in background');
        }
    }
}
