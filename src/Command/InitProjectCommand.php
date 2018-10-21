<?php


namespace Eliepse\Deployer\Command;

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
//            ->addOption("configFIle", "-F", InputArgument::OPTIONAL, "The path to the configuration file of the project.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");

        $project = Project::find($name);

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