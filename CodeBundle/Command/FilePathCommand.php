<?php
namespace Bp\CodeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FilePathCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('code:browse:file')
            ->setDescription('Returns the filepath of given class')
            ->addArgument('class', InputArgument::REQUIRED, 'What class are you looking for?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getArgument('class');

        $output->writeln($class);
    }
}
