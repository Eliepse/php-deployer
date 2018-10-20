<?php


namespace Tests\Unit;


use Eliepse\Deployer\Project\Project;
use PHPUnit\Framework\TestCase;

class ReleaseTest extends TestCase
{

    public function testDelete()
    {
        $project = Project::find("test_deploy", base_path("tests/fixtures/projects"));

        $project->initialize();

        $release = $project->deploy();

        $this->assertDirectoryExists($project->getDeployPath() . "/releases/" . $release->getFolderName());

        $release->delete();

        $this->assertDirectoryNotExists($project->getDeployPath() . "/releases/" . $release->getFolderName());

        $project->destroy();

        $this->assertDirectoryNotExists($project->getDeployPath());
    }

}