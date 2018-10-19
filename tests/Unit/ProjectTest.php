<?php

namespace Tests\Unit;

use Eliepse\Deployer\Project\Project;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{


    public function testLoad()
    {
        $project = Project::find("test_dry", base_path("tests/fixtures/projects"));

        $this->assertEquals("test_dry", $project->getName());
        $this->assertEquals("/path/to/project", $project->getDeployPath());
        $this->assertEquals("git@github.com:Username/repository.git", $project->getGitUrl());
        $this->assertEquals("dev", $project->getBranch());
        $this->assertEquals(3, $project->getReleaseHistory());
    }

}