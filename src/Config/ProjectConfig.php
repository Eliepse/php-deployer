<?php


namespace Eliepse\Deployer\Config;


class ProjectConfig extends Config
{
    protected $required = ["deploy_path", "git_url"];
}