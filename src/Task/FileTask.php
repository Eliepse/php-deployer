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
        return new self($name, base_path("resources/tasks/$name.php"));
    }


    /**
     * FileTask constructor.
     * @param string $name
     * @param string $filepath
     * @throws TaskNotFoundException
     */
    public function __construct(string $name, string $filepath)
    {
        if (!file_exists($filepath))
            throw new TaskNotFoundException("Task file not found at : $filepath");

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