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


    public function testInit()
    {
        $project = Project::find("test_deploy", base_path("tests/fixtures/projects"));

        $project->initialize();

        $this->assertTrue($project->isInitialized());
        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy"));
        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy/shared/resources"));
        $this->assertFileExists(base_path("tests/fixtures/temp/test_deploy/shared/LICENSE.md"));
    }


    public function testDestroy()
    {
        $project = Project::find("test_deploy", base_path("tests/fixtures/projects"));

        $this->assertTrue($project->isInitialized());

        $project->destroy();

        $this->assertFalse($project->isInitialized());
        $this->assertDirectoryNotExists(base_path("tests/fixtures/temp/test_deploy"));
    }

}