<?php
namespace Bp\CodeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PathCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('code:path')
            ->setDescription('Returns the absolute file path given template logical name')
            ->addArgument('lookup', InputArgument::REQUIRED, 'What template are you looking for?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // gather arguments
        $lookup = $input->getArgument('lookup');

        $container = $this->getContainer();
        $controller = $container->get('bp.code.helpers');

        $file_path = $controller->pathAction($lookup);
        $output->writeln($file_path);
    }
}
