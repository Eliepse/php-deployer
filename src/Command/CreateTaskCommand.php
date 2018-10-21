<?php


namespace Eliepse\Deployer\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTaskCommand extends Command
{
    private $config_path;


    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->config_path = base_path("resources/tasks/");
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
        $filepath = $this->config_path . "$name.php";

        if (file_exists($filepath)) {

            $output->writeln("<error>Task $name already exists !</error>");
            $output->writeln("<info>Nothing has been done.</info>");

            return;
        }

        file_put_contents($filepath, 'echo <?= $test ?>');

        $output->writeln("<info>Task created at $filepath.</info>");
    }

}