<?php
namespace Bp\CodeBundle\Command;

use Bp\CodeBundle\Model\Lookup;
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
            ->setDescription('Get the symfony path for a named resource')
            ->addArgument('lookup', InputArgument::REQUIRED, 'What resource are you looking for?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // gather arguments and options
        $lookup = new Lookup($input->getArgument('lookup'), $this->getContainer());

        // perform resource lookup
        $resource_path = $lookup->getPath();

        $output->writeln($resource_path);
    }
}
