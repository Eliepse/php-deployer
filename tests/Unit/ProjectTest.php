<?php

namespace Tests\Unit;

use function Eliepse\Deployer\base_path;
use Eliepse\Deployer\Deployer;
use Tests\TestBase;

class ProjectTest extends TestBase
{

    public function testLoad()
    {
        $project = $this->deployer->getProject("test_dry");

        $this->assertEquals("test_dry", $project->getName());
        $this->assertEquals("/home/vagrant/www/deploy-path", $project->getDeployPath());
        $this->assertEquals("git@github.com:Username/repository-name.git", $project->getGitUrl());
        $this->assertEquals("dev", $project->getBranch());
        $this->assertEquals(3, $project->getReleaseHistory());
    }


    public function testInit()
    {
        $project = $this->deployer->getProject("test_deploy");

        $project->initialize();

        $this->assertTrue($project->isInitialized());
        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy"));
        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy/shared/resources"));
        $this->assertFileExists(base_path("tests/fixtures/temp/test_deploy/shared/LICENSE.md"));
    }


    public function testDeploy()
    {
        $release = $this->deployer->getProject("test_deploy")->deploy();

        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy/releases/{$release->getFolderName()}"));
        $this->assertTrue(is_link(base_path("tests/fixtures/temp/test_deploy/releases/{$release->getFolderName()}/resources")));
        $this->assertTrue(is_link(base_path("tests/fixtures/temp/test_deploy/current")));
    }


    public function testDestroy()
    {
        $project = $this->deployer->getProject("test_deploy");

        $this->assertTrue($project->isInitialized());

        $project->destroy();

        $this->assertFalse($project->isInitialized());
        $this->assertDirectoryNotExists(base_path("tests/fixtures/temp/test_deploy"));
    }

}