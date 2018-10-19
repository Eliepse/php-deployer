<?php

namespace Tests\Unit;

use Eliepse\Deployer\Project\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{


    public function testLoad()
    {
        $project = Project::find("test", base_path("tests/fixtures/projects"));

        $this->assertEquals("test", $project->getName());
        $this->assertEquals("/path/to/project", $project->getDeployPath());
        $this->assertEquals("git@github.com:Eliepse/php-deployer.git", $project->getGitUrl());
        $this->assertEquals("dev", $project->getBranch());
        $this->assertEquals(3, $project->getReleaseHistory());
    }

}