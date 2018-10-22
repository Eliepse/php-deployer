<?php

namespace Eliepse\Deployer\Release;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Eliepse\Deployer\Compiler\CompilerResource;
use Eliepse\Deployer\Compiler\ProjectCompiler;
use Eliepse\Deployer\Project\Project;
use Eliepse\Deployer\Task\FileTask;
use Eliepse\Deployer\Task\Task;

class Release implements CompilerResource
{

    /**
     * @var Carbon
     */
    protected $name;

    /**
     * @var Carbon
     */
    private $deploy_started_at;

    /**
     * @var Carbon
     */
    private $deploy_ended_at;

    /**
     * @var Project
     */
    protected $project;


    /**
     * Release constructor.
     * @param Project $belongsTo
     * @param string|null $name
     */
    public function __construct(Project $belongsTo, string $name = null)
    {
        $this->project = $belongsTo;
        $this->name = $time ?? Carbon::now()->format("YmdHis");
    }


    public function getName(): Carbon { return $this->name; }


    public function getDeployStartedAt(): Carbon { return $this->deploy_started_at; }


    public function getDeployEndedAt(): Carbon { return $this->deploy_ended_at; }


    public function getFolderName(): string { return $this->name; }


    public function getCompilingData(): array
    {
        return [
            "release_name" => $this->getFolderName(),
            "release_path" => $this->project->getDeployPath() . "/releases/" . $this->getFolderName(),
        ];
    }


    /**
     * Set the time when the deployment started. If null, "now" is used.
     * @param Carbon|null $started_at The date deploy started
     * @return Release
     */
    public function setDeployStartedAt(Carbon $started_at = null): self
    {
        $this->deploy_started_at = $started_at ?? Carbon::now();

        return $this;
    }


    /**
     * Set the time when the deployment ended. If null, "now" is used.
     * @param Carbon|null $ended_at The date deploy ended
     * @return Release
     */
    public function setDeployEndedAt(Carbon $ended_at = null): self
    {
        $this->deploy_ended_at = $ended_at ?? Carbon::now();

        return $this;
    }


    public function getDeployDuration(): CarbonInterval
    {
        return $this->deploy_started_at->diffAsCarbonInterval($this->deploy_ended_at);
    }


    /**
     * @return Task
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     * @throws \Eliepse\Deployer\Exception\TaskRunFailedException
     */
    public function delete(): Task
    {
        $task = new FileTask('clean', base_path("/resources/tasks/clean.php"));

        (new ProjectCompiler($this->project, $this))->compile($task);

        $task->run();

        return $task;
    }

}