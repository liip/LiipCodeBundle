<?php
namespace Liip\CodeBundle\Command;

use Liip\CodeBundle\Model\Lookup;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PathCommand extends CodeCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('code:path')
            ->setDescription('Get the symfony path for a named resource');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lookup = new Lookup($input->getArgument('lookup'), $input->getOption('type'), $this->getContainer());

        // perform resource lookup
        $resource_path = $lookup->getPath();

        $output->writeln($resource_path);
    }
}
