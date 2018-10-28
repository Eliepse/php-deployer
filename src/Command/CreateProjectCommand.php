<?php


namespace Eliepse\Deployer\Command;


use function Eliepse\Deployer\base_path;
use Eliepse\Deployer\Config\ProjectConfig;
use Eliepse\Deployer\Deployer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProjectCommand extends Command
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
        $this->setName("project:create")
            ->setDescription("Create a new project configuration file")
            ->addArgument("name", InputArgument::REQUIRED, "The name of the project.");
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $filepath = $this->deployer->getProjectsPath() . "/$name.yaml";

        if (file_exists($filepath)) {

            $output->writeln("<error>Config file for the project $name already exists !</error>");
            $output->writeln("<info>Nothing has been done.</info>");

            return;
        }

        copy(base_path("/resources/projects/sample.yaml"), $filepath);

        $output->writeln("<info>Project configuration created at $filepath.</info>");
    }


}