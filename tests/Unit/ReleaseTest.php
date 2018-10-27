<?php


namespace Tests\Unit;


use Eliepse\Deployer\Deployer;
use Eliepse\Deployer\Exception\ReleaseFailedException;
use Tests\TestBase;

class ReleaseTest extends TestBase
{

    public function testDelete()
    {
        $project = $this->deployer->getProject("test_deploy");

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
        $project = $this->deployer->getProject("test_fail");

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