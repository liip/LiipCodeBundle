<?php
namespace Liip\CodeBundle\Command;

use Liip\CodeBundle\Model\Lookup;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LocateCommand extends CodeCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('code:locate')
            ->setDescription('Find the filepath of a named resource');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lookup = new Lookup($input->getArgument('lookup'), $input->getOption('type'), $this->getContainer());

        $file_path = $lookup->getFilePath();

        $output->writeln($file_path);
    }
}
