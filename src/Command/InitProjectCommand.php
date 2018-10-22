<?php


namespace Eliepse\Deployer\Command;

use Eliepse\Deployer\Config\ProjectConfig;
use Eliepse\Deployer\Project\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitProjectCommand extends Command
{

    protected function configure()
    {
        $this->setName("project:init")
            ->setDescription("Initialize a project")
            ->addArgument("name", InputArgument::REQUIRED, "The name of the project.");
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Eliepse\Deployer\Exception\CompileException
     * @throws \Eliepse\Deployer\Exception\ConfigurationException
     * @throws \Eliepse\Deployer\Exception\JsonException
     * @throws \Eliepse\Deployer\Exception\ProjectException
     * @throws \Eliepse\Deployer\Exception\TaskNotFoundException
     * @throws \Eliepse\Deployer\Exception\TaskRunFailedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");

        $project = new Project($name, ProjectConfig::load(base_path("/resources/projects/$name.json")));

        if ($project->isInitialized()) {

            $output->writeln("<error>The project has already been initialized.</error>");
            $output->writeln("<info>Aborted.</info>");

            return;

        }

        $output->writeln("<info>Starting initialization...</info>");

        $project->initialize();

        if ($project->isInitialized()) {

            $output->writeln("<info>Initialization is a success !</info>");

        } else {

            $output->writeln("<error>Failed to initialized...</error>");

        }

    }


}