<?php

namespace Eliepse\Deployer\Project;

use Eliepse\Deployer\Compiler\Compiler;
use Eliepse\Deployer\Compiler\CompilerResource;
use Eliepse\Deployer\Compiler\ProjectCompiler;
use Eliepse\Deployer\Config\Config;
use Eliepse\Deployer\Deployer;
use Eliepse\Deployer\Exception\ProjectException;
use Eliepse\Deployer\Exception\TaskRunFailedException;
use Eliepse\Deployer\Release\Release;
use Eliepse\Deployer\Release\RunnableRelease;

class Project implements CompilerResource
{
    /**
     * @var Deployer
     */
    private $deployer;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Config
     */
    protected $config;


    /**
     * Project constructor.
     * @param string $name
     * @param Config $config
     * @param Deployer $deployer
     */
    public function __construct(string $name, Config $config, Deployer $deployer)
    {
        $this->name = $name;

        $config->isValid();
        $this->config = $config;

        $this->deployer = $deployer;
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

        $task = $this->deployer->getFileTask('init');

        (new ProjectCompiler($this, new Release($this, $this->deployer)))->compile($task);

        $this->deployer->getLogger()
            ->warning("Project {$this->getName()}: initialized");

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

        $task = $this->deployer->getFileTask('destroy');

        (new Compiler($this))->compile($task);

        $this->deployer->getLogger()
            ->warning("Project {$this->getName()}: destroyed");

        $task->run();
    }


    /**
     * @param RunnableRelease $release
     * @return RunnableRelease
     * @throws ProjectException
     * @throws TaskRunFailedException
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\ReleaseFailedException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     */
    public function deploy(RunnableRelease $release = null): RunnableRelease
    {
        if (!$this->isInitialized())
            throw new ProjectException("The project has not been initialized.");

        $release = $release ?? new RunnableRelease($this, $this->getTasksSequence(), $this->deployer);

        $release->runSequence();

        $this->deployer->getLogger()
            ->info("Project {$this->getName()}: deployed successfully release {$release->getName()}");

        return $release;
    }


    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        if (!is_dir($this->getDeployPath())) return false;

        if (is_link($this->getDeployPath() . "/current")) return true;

        if (!is_dir($this->getDeployPath() . "/releases")) return false;

        if (!is_dir($this->getDeployPath() . "/shared")) return false;

        return true;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function getDeployPath(): string
    {
        return $this->config->get("deploy_path");
    }


    /**
     * @return string
     */
    public function getGitUrl(): string
    {
        return $this->config->get("git_url");
    }


    /**
     * @return string
     */
    public function getBranch(): string
    {
        return $this->config->get("git_branch", "master");
    }


    /**
     * @return int
     */
    public function getReleaseHistory(): int
    {
        return $this->config->get("git_branch", 3);
    }


    /**
     * @return array
     */
    public function getTasksSequence(): array
    {
        return $this->config->get("tasks_sequence", ["release", "links", "activate", "history"]);
    }

    /**
     * @return array
     */
    public function getSharedFolders(): array
    {
        return $this->config->get("shared_folders", []);
    }


    /**
     * @return array
     */
    public function getSharedFiles(): array
    {
        return $this->config->get("shared_files", []);
    }


    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->config->get("shared_files", []);
    }


    /**
     * @return array
     */
    public function getCompilingData(): array
    {
        return [
            "project_name" => $this->getName(),
            "project_path" => $this->getDeployPath(),
            "project_repository" => $this->getGitUrl(),
            "project_branch" => $this->getBranch(),
            "project_release_history" => $this->getReleaseHistory(),
            "project_shared_folders" => $this->getSharedFolders(),
            "project_shared_files" => $this->getSharedFiles(),
            "project_links" => $this->getLinks(),
        ];
    }
}