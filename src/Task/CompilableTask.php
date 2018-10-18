<?php


namespace Eliepse\Deployer\Task;


use Eliepse\Deployer\Compiler\ShouldCompile;
use Eliepse\Deployer\Exception\CompileException;

class CompilableTask extends Task implements ShouldCompile
{

    /**
     * The uncompiled command
     * @var string
     */
    protected $command_raw;


    public function __construct(string $name, string $command_raw)
    {
        parent::__construct($name, "");

        $this->command_raw = $command_raw;
    }


    /**
     * @throws CompileException
     */
    public function run(): bool
    {
        if (empty($this->command))
            throw new CompileException("Trying to run an uncompiled task.");

        return parent::run();
    }


    public function getRaw(): string
    {
        return $this->command_raw ?? "";
    }


    public function setCompiled(string $compiled): void
    {
        $this->command = $compiled;
    }
}