<?php

namespace Eliepse\Deployer\Project;

use Eliepse\Deployer\Compiler\Compiler;
use Eliepse\Deployer\Compiler\CompilerResource;
use Eliepse\Deployer\Compiler\ProjectCompiler;
use Eliepse\Deployer\Config\Config;
use Eliepse\Deployer\Exception\ProjectException;
use Eliepse\Deployer\Exception\TaskRunFailedException;
use Eliepse\Deployer\Release\Release;
use Eliepse\Deployer\Release\RunnableRelease;
use Eliepse\Deployer\Task\FileTask;

class Project implements CompilerResource
{
    private $name;

    private $deploy_path;

    private $git_url;

    private $branch = "master";

    private $release_history = 3;

    private $shared_folders = [];

    private $links = [];

    private $tasks_sequence = ["release", "links", "activate", "history"];


    /**
     * @param string $name
     * @param string|null $folderPath
     * @return Project
     * @throws \Eliepse\Deployer\Exception\ConfigurationException
     * @throws \Eliepse\Deployer\Exception\JsonException
     */
    public static function find(string $name, string $folderPath = null): self
    {
        $path = $folderPath ? "$folderPath/$name.json" : base_path("resources/projects/$name.json");

        $config = Config::load($path, new Config(["deploy_path", "git_url"]));

        $project = new self();

        $project->name = $name;

        $project->hydrate($config);

        return $project;
    }


    /**
     * @param Config $config
     */
    private function hydrate(Config $config): void
    {
        foreach ($config->getAll() as $key => $value) {

            $this->$key = $value;

        }
    }


    /**
     * @throws ProjectException
     * @throws TaskRunFailedException
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     */
    public function initialize(): void
    {
        if ($this->isInitialized())
            throw new ProjectException("The project has already been initialized.");

        $task = FileTask::find('init');

        (new ProjectCompiler($this, new Release($this)))->compile($task);

        $task->run();
    }


    /**
     * @throws ProjectException
     * @throws TaskRunFailedException
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     */
    public function destroy(): void
    {
        if (!$this->isInitialized())
            throw new ProjectException("The project has not been initialized.");

        $task = FileTask::find('remove');

        (new Compiler($this))->compile($task);

        $task->run();
    }


    /**
     * @throws ProjectException
     * @throws TaskRunFailedException
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     * @todo Allow to provide custom release as parameter ?
     */
    public function deploy(): Release
    {
        if (!$this->isInitialized())
            throw new ProjectException("The project has not been initialized.");

        return (new RunnableRelease($this, $this->tasks_sequence))->runSequence();
    }


    public function isInitialized(): bool
    {
        if (!is_dir($this->getDeployPath())) return false;

        if (is_link($this->getDeployPath() . "/current")) return true;

        if (!is_dir($this->getDeployPath() . "/releases")) return false;

        if (!is_dir($this->getDeployPath() . "/shared")) return false;

        return true;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return mixed
     */
    public function getDeployPath()
    {
        return $this->deploy_path[0] === "/" ? $this->deploy_path : base_path($this->deploy_path);
    }


    /**
     * @return mixed
     */
    public function getGitUrl()
    {
        return $this->git_url;
    }


    /**
     * @return string
     */
    public function getBranch(): string
    {
        return $this->branch;
    }


    /**
     * @return int
     */
    public function getReleaseHistory(): int
    {
        return $this->release_history;
    }


    public function getSharedFolders(): array
    {
        $this->shared_folders;
    }


    public function getCompilingData(): array
    {
        return [
            "project_name"            => $this->getName(),
            "project_path"            => $this->getDeployPath(),
            "project_repository"      => $this->getGitUrl(),
            "project_branch"          => $this->getBranch(),
            "project_release_history" => $this->getReleaseHistory(),
            "project_shared_folders"  => $this->shared_folders,
            "project_links"           => $this->links,
        ];
    }
}