<?php
namespace Liip\CodeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CodeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->addArgument('lookup', InputArgument::REQUIRED, 'Id or name of the resource you are looking for.')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Type of the resource (class|service|template)');
    }
}
