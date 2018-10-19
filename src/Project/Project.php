<?php

namespace Eliepse\Deployer\Project;

use Eliepse\Deployer\Compiler\CompilerResource;
use Eliepse\Deployer\Compiler\ProjectCompiler;
use Eliepse\Deployer\Config\Config;
use Eliepse\Deployer\Exception\TaskRunFailedException;
use Eliepse\Deployer\Task\FileTask;

class Project implements CompilerResource
{
    private $name;

    private $deploy_path;

    private $git_url;

    private $branch = "master";

    private $release_history = 3;

    private $shared_folders = [];


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
     * @param string $name
     * @param string|null $folderPath
     * @return Project
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\ConfigurationException
     * @throws \Eliepse\Deployer\Exception\JsonException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     * @throws TaskRunFailedException
     */
    public static function init(string $name, string $folderPath = null): self
    {
        $project = static::find($name, $folderPath);

        $task = FileTask::find('init');

        (new ProjectCompiler($project, new Release))->compile($task);

        $task->run();

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
            "project_name"           => $this->getName(),
            "project_path"           => $this->getDeployPath(),
            "project_repository"     => $this->getGitUrl(),
            "project_branch"         => $this->getBranch(),
            "project_shared_folders" => $this->shared_folders,
        ];
    }
}