<?php

namespace Tests\Unit;

use Eliepse\Deployer\Config\Config;
use Eliepse\Deployer\Config\ProjectConfig;
use Eliepse\Deployer\Project\Project;
use Eliepse\Deployer\Project\Release;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{

    /**
     * @param string $name
     * @return Project
     * @throws \Eliepse\Deployer\Exception\ConfigurationException
     * @throws \Eliepse\Deployer\Exception\JsonException
     */
    private function loadProject(string $name): Project
    {
        return new Project($name, ProjectConfig::load(base_path("/tests/fixtures/projects/$name.json")));
    }


    public function testLoad()
    {
        $project = $this->loadProject("test_dry");

        $this->assertEquals("test_dry", $project->getName());
        $this->assertEquals("/path/to/project", $project->getDeployPath());
        $this->assertEquals("git@github.com:Username/repository.git", $project->getGitUrl());
        $this->assertEquals("dev", $project->getBranch());
        $this->assertEquals(3, $project->getReleaseHistory());
    }


    public function testInit()
    {
        $project = $this->loadProject("test_deploy");

        $project->initialize();

        $this->assertTrue($project->isInitialized());
        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy"));
        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy/shared/resources"));
        $this->assertFileExists(base_path("tests/fixtures/temp/test_deploy/shared/LICENSE.md"));
    }


    public function testDeploy()
    {
        $project = $this->loadProject("test_deploy");

        $release = $project->deploy();

        $this->assertDirectoryExists(base_path("tests/fixtures/temp/test_deploy/releases/{$release->getFolderName()}"));
        $this->assertTrue(is_link(base_path("tests/fixtures/temp/test_deploy/releases/{$release->getFolderName()}/resources")));
        $this->assertTrue(is_link(base_path("tests/fixtures/temp/test_deploy/current")));
    }


    public function testDestroy()
    {
        $project = $this->loadProject("test_deploy");

        $this->assertTrue($project->isInitialized());

        $project->destroy();

        $this->assertFalse($project->isInitialized());
        $this->assertDirectoryNotExists(base_path("tests/fixtures/temp/test_deploy"));
    }

}