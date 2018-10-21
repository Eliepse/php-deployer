<?php


namespace Eliepse\Deployer\Command;


use Eliepse\Deployer\Project\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class DestroyProjectCommand extends Command
{

    protected function configure()
    {
        $this->setName("project:destroy")
            ->setDescription("Permanently all files of an initialized project (except config file).")
            ->addArgument("name", InputArgument::REQUIRED, "The name of the project.");
//            ->addOption("configFIle", "-F", InputArgument::OPTIONAL, "The path to the configuration file of the project.");
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");

        /** @var QuestionHelper $help */
        $help = $this->getHelper("question");
        $confirmDestroy = new ConfirmationQuestion("Are you sure you want to delete the project '$name'?", false);

        if (!$help->ask($input, $output, $confirmDestroy)) {

            $output->writeln("Abort.");

            exit();
        }

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $project = Project::find($name);

        $output->writeln("Start destroying files of project '$name'.");

        $project->destroy();

        $output->writeln("Project '$name' destroyed.");
    }

}