<?php


namespace Tests\Unit;


use Eliepse\Deployer\Config\ProjectConfig;
use Eliepse\Deployer\Exception\ReleaseFailedException;
use Eliepse\Deployer\Project\Project;
use PHPUnit\Framework\TestCase;

class ReleaseTest extends TestCase
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


    public function testDelete()
    {
        $project = $this->loadProject("test_deploy");

        $project->initialize();

        $release = $project->deploy();

        $this->assertDirectoryExists($project->getDeployPath() . "/releases/" . $release->getFolderName());

        $release->delete();

        $this->assertDirectoryNotExists($project->getDeployPath() . "/releases/" . $release->getFolderName());

        $project->destroy();

        $this->assertDirectoryNotExists($project->getDeployPath());
    }


    public function testRunFailed()
    {
        $project = $this->loadProject("test_fail");

        $project->initialize();

        $this->assertDirectoryExists($project->getDeployPath());

        try {

            $project->deploy();

        } catch (ReleaseFailedException $exception) {

            $this->assertInstanceOf(ReleaseFailedException::class, $exception);

        }

        $project->destroy();

        $this->assertDirectoryNotExists($project->getDeployPath());
    }

}