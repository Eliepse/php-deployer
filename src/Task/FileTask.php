<?php


namespace Eliepse\Deployer\Task;


use Eliepse\Deployer\Compiler\ShouldCompileFile;
use Eliepse\Deployer\Exception\TaskNotFoundException;

class FileTask extends CompilableTask implements ShouldCompileFile
{
    /**
     * @var string
     */
    protected $filepath;


    /**
     * @param string $name
     * @return FileTask
     * @throws TaskNotFoundException
     */
    public static function find(string $name): self
    {
        $filepath = base_path("resources/tasks/$name.php");

        if (!file_exists($filepath))
            throw new TaskNotFoundException("Task file not found at : $filepath");

        return new self($name, $filepath);
    }


    public function __construct(string $name, string $filepath)
    {
        parent::__construct($name, "");

        $this->filepath = $filepath;
        $this->command_raw = $this->getUncompiled();
    }


    public function getUncompiled(): string
    {
        return file_get_contents($this->getSourcePath());
    }


    public function getSourcePath(): string
    {
        return $this->filepath;
    }
}