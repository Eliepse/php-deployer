<?php


namespace Eliepse\Deployer;


use Eliepse\Deployer\Config\ProjectConfig;
use Eliepse\Deployer\Project\Project;
use Eliepse\Deployer\Task\FileTask;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Deployer
{
    /**
     * @var string
     */
    private $projects_folder;

    /**
     * @var string
     */
    private $tasks_folder;

    /**
     * @var string
     */
    private $task_base_folder;

    /**
     * @var LoggerInterface
     */
    private $logger;


    public function __construct(string $projectsFolder = null, string $tasksFolder = null)
    {
        $this->projects_folder = $projectsFolder ?? realpath(base_path('/resources/projects'));

        $this->task_base_folder = realpath(base_path('/resources/tasks'));

        $this->tasks_folder = $tasksFolder ?? $this->task_base_folder;

        $this->logger = new NullLogger();
    }


    public function setProjectsPath(string $path): void
    {
        $this->projects_folder = $path;
    }


    public function setTasksPath(string $path): void
    {
        $this->tasks_folder = $path;
    }


    public function getProjectsPath(): string
    {
        return $this->projects_folder;
    }


    public function getTasksPath(): string
    {
        return $this->tasks_folder;
    }


    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }


    /**
     * @param string $name
     * @return Project
     * @throws Exception\ConfigurationException
     */
    public function getProject(string $name): Project
    {
        return new Project($name, ProjectConfig::load($this->projects_folder . "/$name.yaml"), $this);
    }


    /**
     * @param string $name
     * @return FileTask
     * @throws Exception\TaskNotFoundException
     */
    public function getFileTask(string $name): FileTask
    {
        if (file_exists($this->tasks_folder . "/$name.php"))
            return new FileTask($name, $this->tasks_folder . "/$name.php", $this);
        else
            return new FileTask($name, $this->task_base_folder . "/$name.php", $this);
    }


}