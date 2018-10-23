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
     * @var Deployer|null
     */
    private static $uniqueInstance = null;

    /**
     * @var string
     */
    private $projects_folder;

    /**
     * @var string
     */
    private $tasks_folder;

    /**
     * @var LoggerInterface
     */
    private $logger;


    protected function __construct(string $projectsFolder = null, string $tasksFolder = null)
    {
        $this->projects_folder = $projectsFolder ?? realpath(base_path('/resources/projects'));

        $this->tasks_folder = $tasksFolder ?? realpath(base_path('/resources/tasks'));

        $this->logger = new NullLogger();
    }


    final private function __clone() { }


    public static function make(string $projectsFolder = null, string $tasksFolder = null): self
    {
        self::$uniqueInstance = new self(...func_get_args());

        return self::$uniqueInstance;
    }


    public static function getInstance(): Deployer
    {
        if (self::$uniqueInstance === null) {
            self::$uniqueInstance = new self(...func_get_args());
        }

        return self::$uniqueInstance;
    }


    /**
     * @param string $name
     * @return Project
     * @throws Exception\ConfigurationException
     * @throws Exception\JsonException
     */
    public static function project(string $name): Project
    {
        return self::getInstance()->getProject($name);
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
     * @throws Exception\JsonException
     */
    public function getProject(string $name): Project
    {
        return new Project($name, ProjectConfig::load($this->projects_folder . "/$name.json"));
    }


    /**
     * @param string $name
     * @return FileTask
     * @throws Exception\TaskNotFoundException
     */
    public function getFileTask(string $name): FileTask
    {
        return new FileTask($name, $this->tasks_folder . "/$name.php");
    }


}