<?php

namespace Tests\Unit;

use Eliepse\Deployer\Deployer;
use Tests\TestBase;

class ProjectTest extends TestBase
{

    public function testLoad()
    {
        $project = Deployer::getInstance()->getProject("test_dry");

        $this->assertEquals("test_dry", $project->getName());
        $this->assertEquals("/path/to/project", $project->getDeployPath());
        $this->assertEquals("git@github.com:Username/repository.git", $project->getGitUrl());
        $this->assertEquals("dev", $project->getBranch());
        $this->assertEquals(3, $project->getReleaseHistory());
    }


    public function testInit()
    {
        $project = Deployer::getInstance()->getProject("test_deploy");

        $project->initialize();

        $this->assertTrue($project->isInitialized());
        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy"));
        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy/shared/resources"));
        $this->assertFileExists(base_path("tests/fixtures/temp/test_deploy/shared/LICENSE.md"));
    }


    public function testDeploy()
    {
        $release = Deployer::project("test_deploy")->deploy();

        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy/releases/{$release->getFolderName()}"));
        $this->assertTrue(is_link(base_path("tests/fixtures/temp/test_deploy/releases/{$release->getFolderName()}/resources")));
        $this->assertTrue(is_link(base_path("tests/fixtures/temp/test_deploy/current")));
    }


    public function testDestroy()
    {
        $project = Deployer::getInstance()->getProject("test_deploy");

        $this->assertTrue($project->isInitialized());

        $project->destroy();

        $this->assertFalse($project->isInitialized());
        $this->assertDirectoryNotExists(base_path("tests/fixtures/temp/test_deploy"));
    }

}