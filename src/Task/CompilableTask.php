<?php


namespace Eliepse\Deployer\Task;


use Eliepse\Deployer\Compiler\ShouldCompile;
use Eliepse\Deployer\Deployer;
use Eliepse\Deployer\Exception\CompileException;
use Eliepse\Deployer\Exception\TaskRunFailedException;

class CompilableTask extends Task implements ShouldCompile
{

    /**
     * The uncompiled command
     * @var string
     */
    protected $command_raw;

    /**
     * @var bool
     */
    protected $compiled = false;


    public function __construct(string $name, string $command_raw, Deployer $deployer)
    {
        parent::__construct($name, "", $deployer);

        $this->command_raw = $command_raw;
    }


    /**
     * @throws CompileException
     * @throws TaskRunFailedException
     */
    public function run(): void
    {
        if (!$this->compiled)
            throw new CompileException("Trying to run an uncompiled task ({$this->name}).");

        parent::run();
    }


    public function getUncompiled(): string
    {
        return $this->command_raw ?? "";
    }


    public function setCompiled(string $compiled): void
    {
        $this->command = $compiled;
        $this->compiled = true;
    }
}