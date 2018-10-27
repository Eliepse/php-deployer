<?php

namespace Eliepse\Deployer\Release;

use Eliepse\Deployer\Compiler\ProjectCompiler;
use Eliepse\Deployer\Deployer;
use Eliepse\Deployer\Exception\ReleaseFailedException;
use Eliepse\Deployer\Exception\TaskRunFailedException;
use Eliepse\Deployer\Project\Project;
use Eliepse\Deployer\Task\FileTask;
use Eliepse\Deployer\Task\Task;

class RunnableRelease extends Release
{

    /**
     * The name of tasks to run for this release
     * @var array
     */
    private $tasks_sequence = [];

    /**
     * Tasks that have been performed
     * @var array
     */
    private $runned_tasks = [];


    /**
     * Release constructor.
     * @param Project $belongsTo
     * @param array $tasksSequence
     * @param Deployer $deployer
     * @param string|null $name
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     */
    public function __construct(Project $belongsTo, array $tasksSequence, Deployer $deployer, string $name = null)
    {
        parent::__construct($belongsTo, $deployer, $name);

        $this->setTasksSequence($tasksSequence);
    }


    public function getTasksSequence(): array { return $this->tasks_sequence; }


    /**
     * @param array $tasks_sequence
     * @return Release
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     */
    public function setTasksSequence(array $tasks_sequence): Release
    {
        $this->tasks_sequence = [];

        foreach ($tasks_sequence as $name)
            $this->tasks_sequence[] = $this->deployer->getFileTask($name);

        return $this;
    }


    public function isRunning(): bool
    {
        return $this->getDeployStartedAt() && !$this->getDeployEndedAt();
    }


    public function isTerminated(): bool
    {
        return $this->getDeployEndedAt() !== null;
    }


    public function isSuccess(): bool
    {
        return count(array_filter($this->runned_tasks, function (Task $task) { return !$task->getProcess()->isSuccessful(); })) === 0;
    }


    /**
     * Run the tasks sequence
     * @return RunnableRelease
     * @throws ReleaseFailedException
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     * @throws TaskRunFailedException
     */
    public function runSequence(): self
    {
        $logger = $this->deployer->getLogger();

        $this->compileTasks();

        $logger->info("Release {$this->name}: starting");

        $this->setDeployStartedAt();

        /** @var Task $task */
        foreach ($this->tasks_sequence as $task) {

            $this->runned_tasks[] = $task;

            try {

                $task->run();

            } catch (TaskRunFailedException $exception) {

                $this->setDeployEndedAt();
                $this->delete();

                $logger->error("Release {$this->name}: failed", [
                    "last_task" => $this->getLastRunnedTask()->getName(),
                    "project"   => $this->project->getName(),
                ]);

                throw new ReleaseFailedException($this, "The task '{$task->getName()}' failed.");

            }

        }

        $this->setDeployEndedAt();

        $logger->info("Release {$this->name}: ended (in {$this->getDeployDuration()->minutes} min {$this->getDeployDuration()->seconds} s)");

        return $this;
    }


    private function compileTasks(): void
    {
        $compiler = new ProjectCompiler($this->project, $this);

        /** @var FileTask $task */
        foreach ($this->tasks_sequence as $task)
            $compiler->compile($task);
    }


    public function getRunnedTasks(): array
    {
        return $this->runned_tasks;
    }


    /**
     * @return Task|null
     */
    public function getLastRunnedTask()
    {
        $i = count($this->runned_tasks);

        return $i > 0 ? $this->runned_tasks[ $i - 1 ] : null;
    }


}