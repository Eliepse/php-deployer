<?php


namespace Eliepse\Deployer\Task;


use Carbon\Carbon;
use Carbon\CarbonInterval;
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

    /**
     * @var Carbon|null
     */
    protected $started_at;

    /**
     * @var Carbon|null
     */
    protected $ended_at;

    /**
     * @var Deployer
     */
    private $deployer;


    public function __construct(string $name, string $command, Deployer $deployer)
    {
        $this->name = $name;
        $this->command = $command;
        $this->deployer = $deployer;
    }


    /**
     * @throws TaskRunFailedException
     */
    public function run(): void
    {
        $this->started_at = Carbon::now();

        $this->process = new Process($this->command);

        $this->process->setTimeout($this->timeout);

        $this->process->run();

        $this->ended_at = Carbon::now();

        if (!$this->process->isSuccessful()) {

            $this->deployer
                ->getLogger()
                ->error("Task {$this->getName()}: failed", [
                    "OUT" => $this->getOutput(),
                    "ERR" => $this->getErrorOutput(),
                ]);

            throw new TaskRunFailedException($this->process->getExitCodeText(), $this->process->getExitCode());
        }

        $this->deployer
            ->getLogger()
            ->debug("Task {$this->getName()}: ended successfully ({$this->getExecutionTime()} ms)");
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
     * @return CarbonInterval
     */
    public function getExecutionTime(): CarbonInterval
    {
        return $this->started_at->diffAsCarbonInterval($this->ended_at);
    }

}