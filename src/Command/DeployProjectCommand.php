<?php


namespace Eliepse\Deployer\Command;


use Eliepse\Deployer\Exception\ReleaseFailedException;
use Eliepse\Deployer\Project\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeployProjectCommand extends Command
{

    protected function configure()
    {
        $this->setName("project:deploy")
            ->setDescription("Delpoy a new release of a project")
            ->addArgument("name", InputArgument::REQUIRED, "The name of the project.");
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $project = Project::find($name);

        $output->writeln("Starting deployement...");

        try {

            $release = $project->deploy();
            $duration = $release->getDeployDuration();

            $output->writeln("Deployement runned successfully in {$duration->minutes} min {$duration->seconds} s.");

        } catch (ReleaseFailedException $exception) {

            $output->writeln("<error>Deployement failed !</error>\n");

            $task = $exception->getRelease()->getLastRunnedTask();

            $output->writeln("Task '{$task->getName()}' shell output:");
            $output->writeln("=============================");
            $output->writeln("<comment>" . $exception->getRelease()->getLastRunnedTask()->getErrorOutput() . "</comment>");
            $output->writeln("=============================\n");

        }
    }

}