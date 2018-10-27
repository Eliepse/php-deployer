<?php


namespace Eliepse\Deployer\Command;

use Eliepse\Deployer\Config\ProjectConfig;
use Eliepse\Deployer\Deployer;
use Eliepse\Deployer\Project\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitProjectCommand extends Command
{

    /**
     * @var Deployer
     */
    protected $deployer;


    public function __construct(?string $name = null, Deployer $deployer = null)
    {
        parent::__construct($name);

        $this->deployer = $deployer ?? new Deployer();
    }


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

        $project = $this->deployer->getProject($name);

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