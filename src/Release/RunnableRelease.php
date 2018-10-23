<?php

namespace Eliepse\Deployer\Release;

use Eliepse\Deployer\Compiler\ProjectCompiler;
use Eliepse\Deployer\Deployer;
use Eliepse\Deployer\Exception\ReleaseFailedException;
use Eliepse\Deployer\Exception\TaskRunFailedException;
use Eliepse\Deployer\Project\Project;
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
     * @param string|null $name
     */
    public function __construct(Project $belongsTo, array $tasksSequence, string $name = null)
    {
        parent::__construct($belongsTo, $name);

        $this->tasks_sequence = $tasksSequence;
    }


    public function getTasksSequence(): array { return $this->tasks_sequence; }


    /**
     * @param array $tasks_sequence
     * @return Release
     */
    public function setTasksSequence(array $tasks_sequence): Release
    {
        $this->tasks_sequence = $tasks_sequence;

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
     * @throws TaskRunFailedException
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     * @todo Add a logging system and/or allow to use an external logging system
     */
    public function runSequence(): self
    {
        $deployer = Deployer::getInstance();
        $compiler = new ProjectCompiler($this->project, $this);

        $deployer->getLogger()->info("Release {$this->name}: starting");

        $this->setDeployStartedAt();

        foreach ($this->tasks_sequence as $key => $name) {

            $task = $deployer->getFileTask($name);

            $compiler->compile($task);

            try {

                $this->runned_tasks[] = $task;

                $task->run();

            } catch (TaskRunFailedException $exception) {

                $this->setDeployEndedAt();
                $this->delete();

                $deployer->getLogger()->error("Release {$this->name}: failed", [
                    "last_task" => $this->getLastRunnedTask()->getName(),
                    "project"   => $this->project->getName(),
                ]);

                throw new ReleaseFailedException($this, "The task '{$task->getName()}' failed.");

            }
        }

        $this->setDeployEndedAt();

        $deployer->getLogger()->info("Release {$this->name}: ended (in {$this->getDeployDuration()->minutes} min {$this->getDeployDuration()->seconds} s)");

        return $this;
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