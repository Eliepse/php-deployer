<?php

namespace Eliepse\Deployer\Release;

use Eliepse\Deployer\Compiler\ProjectCompiler;
use Eliepse\Deployer\Project\Project;
use Eliepse\Deployer\Task\FileTask;

class RunnableRelease extends Release
{

    /**
     * The tasks sequence to run for this release
     * @var array
     */
    private $tasks_sequence = [];


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


    /**
     * Run the tasks sequence
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     * @throws \Eliepse\Deployer\Exception\TaskRunFailedException
     */
    public function runSequence(): self
    {
        $compiler = new ProjectCompiler($this->project, $this);

        $this->setDeployStartedAt();

        foreach ($this->tasks_sequence as $name) {

            $task = FileTask::find($name);

            $compiler->compile($task);

            // TODO Add a logging system and/or allow to use an external logging system
            $task->run();
        }

        $this->setDeployEndedAt();

        return $this;
    }


}