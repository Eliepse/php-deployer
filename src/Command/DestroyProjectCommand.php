<?php


namespace Eliepse\Deployer\Command;


use Eliepse\Deployer\Deployer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DestroyProjectCommand extends Command
{

    protected function configure()
    {
        $this->setName("project:destroy")
            ->setDescription("Permanently all files of an initialized project (except config file).")
            ->addArgument("name", InputArgument::REQUIRED, "The name of the project.");
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
        $project = Deployer::getInstance()->getProject($name);

        $output->writeln("Start destroying files of project '$name'.");

        $project->destroy();

        $output->writeln("Project '$name' destroyed.");
    }

}