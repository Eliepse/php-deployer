<?php

namespace Tests\Unit;

use Eliepse\Deployer\Compiler\Compiler;
use Eliepse\Deployer\Task\CompilableTask;
use Eliepse\Deployer\Task\FileTask;
use Eliepse\Deployer\Task\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testRun()
    {
        $task = new Task("foo", "echo bar");

        $task->run();

        $this->assertEquals("bar", $task->getOutput());
    }


    public function testRunCompiled()
    {
        $compiler = (new Compiler)->addAdditionalData("foo", "bar");

        $task = new CompilableTask("foo", 'echo <?= $foo ?>');

        $compiler->compile($task);

        $task->run();

        $this->assertEquals("bar", $task->getOutput());
    }


    public function testLoadAndRun()
    {
        $task = new FileTask("test", base_path("/resources/tasks/test.php"));

        (new Compiler)->compile($task);

        $task->run();
        
        $this->assertEquals("Hello world !", $task->getOutput());
    }

}
