<?php
namespace Bp\CodeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LocateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('code:resource:locate')
            ->setDescription('Returns the filesystem absolute path for named resource')
            ->addArgument('resource_name', InputArgument::REQUIRED, 'What resource are you looking for?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // gather arguments and options
        $resource_name = $input->getArgument('resource_name');

        // gather services
        $container = $this->getContainer();
        $controller = $container->get('bp.code.helpers');

        // perform path lookup
        $resource_path = $controller->resourcePathAction($resource_name);
        $file_path = $controller->resourceLocateAction($resource_path);

        $output->writeln($file_path);
    }
}
