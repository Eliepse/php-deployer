<?php


namespace Eliepse\Deployer\Task;


use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Task
{
    /**
     * @var string
     */
    protected $name;

    /**
     * The command to run
     * @var Process
     */
    protected $command;

    /**
     * @var Process
     */
    protected $process;


    public function __construct(string $name, string $command)
    {

        $this->name = $name;
        $this->command = $command;
    }


    public function run(): bool
    {
        $this->process = new Process($this->command);

        $this->process->run();

        return $this->process->isSuccessful();
    }


    public function getCommand(): string
    {
        return $this->command;
    }


    public function getOutput(): string
    {
        return trim($this->process->getOutput());
    }


    public function getErrorOutput(): string
    {
        return trim($this->process->getErrorOutput());
    }
}