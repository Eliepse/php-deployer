<?php


namespace Eliepse\Deployer\Task;


use Carbon\Carbon;
use Eliepse\Deployer\Deployer;
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

    /**
     * The process timeout in seconds
     * @var int
     */
    protected $timeout = 240;

    /**
     * Amount of microseconds the task runned
     * @var int
     */
    protected $exec_time = 0;


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
        $t = Carbon::now();
        $deployer = Deployer::getInstance();

        $this->process = new Process($this->command);

        $this->process->setTimeout($this->timeout);

        $this->process->run();

        $this->exec_time = $t->diffInRealMicroseconds();

        $deployer->getLogger()->debug("Task {$this->getName()}: ended successfully ({$this->getExecutionTime()} ms)");

        if (!$this->process->isSuccessful()) {

            $deployer->getLogger()->error("Task {$this->getName()}: failed", [
                "OUT" => $this->getOutput(),
                "ERR" => $this->getErrorOutput(),
            ]);

            throw new TaskRunFailedException($this->process->getExitCodeText(), $this->process->getExitCode());
        }
    }


    public function getName(): string
    {
        return $this->name;
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


    public function getProcess()
    {
        return $this->process;
    }


    /**
     * Return the execution time of the task
     * @return int Amount of microseconds the task runned
     */
    public function getExecutionTime(): int
    {
        return $this->exec_time;
    }
}