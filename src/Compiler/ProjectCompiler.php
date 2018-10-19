<?php


namespace Eliepse\Deployer\Compiler;


use Eliepse\Deployer\Project\Project;
use Eliepse\Deployer\Project\Release;

class ProjectCompiler extends Compiler
{

    /**
     * @var Project
     */
    private $project;
    /**
     * @var Release
     */
    private $release;


    public function __construct(Project $project, Release $release)
    {
        parent::__construct($project, $release);

        $this->project = $project;
        $this->release = $release;
    }


    public function getData(): array
    {
        return array_merge(
            parent::getData(),
            [
                "releases_path" => $this->project->getDeployPath() . "/releases",
                "shared_path"   => $this->project->getDeployPath() . "/shared",
                "release_path"  => $this->project->getDeployPath() . "/releases/" . $this->release->getFolderName(),
            ]
        );
    }

}