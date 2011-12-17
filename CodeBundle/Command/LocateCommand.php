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
            ->setDescription('Returns the filesystem absolute path for resource name or path')
            ->addArgument('lookup', InputArgument::REQUIRED, 'What resource are you looking for?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // gather arguments and options
        $lookup = $input->getArgument('lookup');

        // gather services
        $container = $this->getContainer();
        $controller = $container->get('bp.code.helpers');

        $resource_path = $lookup;

        // map lookup to path if needed
        if ($lookup[0] !== '@') {
            $resource_path = $controller->resourcePathAction($lookup);
        }

        $file_path = $controller->resourceLocateAction($resource_path);

        $output->writeln($file_path);
    }
}
