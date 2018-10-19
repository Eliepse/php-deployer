<?php


namespace Eliepse\Deployer\Task;


use Eliepse\Deployer\Exception\TaskRunFailedException;
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


    /**
     * @throws TaskRunFailedException
     */
    public function run(): void
    {
        $this->process = new Process($this->command);

        $this->process->run();

        if (!$this->process->isSuccessful())
            throw new TaskRunFailedException($this->process->getExitCodeText(), $this->process->getExitCode());
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