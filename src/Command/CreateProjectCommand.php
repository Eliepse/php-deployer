<?php


namespace Eliepse\Deployer\Command;


use Eliepse\Deployer\Config\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProjectCommand extends Command
{
    private $config_path;


    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->config_path = base_path("resources/projects/");
    }


    protected function configure()
    {
        $this->setName("project:create")
            ->setDescription("Create a new project configuration file")
            ->addArgument("name", InputArgument::REQUIRED, "The name of the project.");
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $filepath = $this->config_path . "$name.json";

        if (file_exists($filepath)) {

            $output->writeln("<error>Config file for the project $name already exists !</error>");
            $output->writeln("<info>Nothing has been done.</info>");

            return;
        }

        $config = new Config();

        // Required elements
        $config->set("deploy_path", "(required) the path where to deploy the project (prefer absolute path)");
        $config->set("git_url", "(required) the git url to get the project");

        // Optional
        $config->set("branch", "master");
        $config->set("release_history", 3);
        $config->set("shared_folders", ["originPath" => "sharedPath"]);
        $config->set("shared_files", ["originPath" => "sharedPath"]);
        $config->set("links", ["sharedPath" => "targetPath"]);
        $config->set("tasks_sequence", ["release", "links", "activate", "history"]);

        file_put_contents($filepath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $output->writeln("<info>Project configuration created at $filepath.</info>");
    }


}