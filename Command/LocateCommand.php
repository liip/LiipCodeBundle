<?php
namespace Bp\CodeBundle\Command;

use Bp\CodeBundle\Model\Lookup;
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
            ->setName('code:locate')
            ->setDescription('Returns the filesystem absolute path for resource name or path')
            ->addArgument('lookup', InputArgument::REQUIRED, 'What resource are you looking for?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lookup = new Lookup($input->getArgument('lookup'), $this->getContainer());

        $file_path = $lookup->locate();

        $output->writeln($file_path);
    }
}