<?php

namespace Eliepse\Deployer\Release;

use Eliepse\Deployer\Compiler\ProjectCompiler;
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
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     * @throws ReleaseFailedException
     * @throws TaskRunFailedException
     * @todo Add a logging system and/or allow to use an external logging system
     */
    public function runSequence(): self
    {
        $compiler = new ProjectCompiler($this->project, $this);

        $this->setDeployStartedAt();

        foreach ($this->tasks_sequence as $name) {

            $task = FileTask::find($name);

            $compiler->compile($task);

            try {

                $this->runned_tasks[] = $task;

                $task->run();

            } catch (TaskRunFailedException $exception) {

                $this->setDeployEndedAt();
                $this->runned_tasks[] = $this->delete();

                throw new ReleaseFailedException();

            }
        }

        $this->setDeployEndedAt();

        return $this;
    }


}