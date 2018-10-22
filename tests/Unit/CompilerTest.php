<?php

namespace Tests\Unit;

use Eliepse\Deployer\Compiler\Compiler;
use Eliepse\Deployer\Task\CompilableTask;
use Tests\TestBase;

class CompilerTest extends TestBase
{

    private function newCompiler(): Compiler
    {
        return (new Compiler)->addAdditionalData("foo", "bar");
    }


    public function testGetData()
    {
        $data = $this->newCompiler()->getData();

        $this->assertArrayHasKey("foo", $data);
        $this->assertEquals("bar", $data["foo"]);
    }


    public function testCompileTask()
    {
        $task = new CompilableTask("foo", 'echo <?= $foo ?>');

        $this->newCompiler()->compile($task);

        $this->assertEquals("echo bar", $task->getCommand());
    }

}