<?php


namespace Eliepse\Deployer\Task;


use Eliepse\Deployer\Compiler\ShouldCompileFile;
use Eliepse\Deployer\Deployer;
use Eliepse\Deployer\Exception\TaskNotFoundException;

class FileTask extends CompilableTask implements ShouldCompileFile
{
    /**
     * @var string
     */
    protected $filepath;


    /**
     * FileTask constructor.
     * @param string $name
     * @param string $filepath
     * @param Deployer $deployer
     * @throws TaskNotFoundException
     */
    public function __construct(string $name, string $filepath, Deployer $deployer)
    {
        if (!file_exists($filepath))
            throw new TaskNotFoundException("Task file not found at : $filepath");

        parent::__construct($name, "", $deployer);

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