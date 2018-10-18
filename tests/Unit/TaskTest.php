<?php

namespace Tests\Unit;

use Eliepse\Deployer\Task\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testRun()
    {
        $task = new Task("foo", "echo bar");

        $this->assertTrue($task->run());
        $this->assertEquals("bar", $task->getOutput());
    }

}
