<?php


namespace Eliepse\Deployer\Command;


use function Eliepse\Deployer\base_path;
use Eliepse\Deployer\Deployer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTaskCommand extends Command
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
        $this->setName("task:create")
            ->setDescription("Create a new task")
            ->addArgument("name", InputArgument::REQUIRED, "The name of the task.");
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $filepath = $this->deployer->getTasksPath() . "/$name.php";

        if (file_exists($filepath)) {

            $output->writeln("<error>Task $name already exists !</error>");
            $output->writeln("<info>Nothing has been done.</info>");

            return;
        }

        copy(base_path("/resources/tasks/test.php"), $filepath);

        $output->writeln("<info>Task created at $filepath.</info>");
    }

}