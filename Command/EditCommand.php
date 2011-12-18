<?php
namespace Liip\CodeBundle\Command;

use Liip\CodeBundle\Model\Lookup;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EditCommand extends CodeCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('code:edit')
            ->setDescription('Edit the source file of a named resource');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lookup = new Lookup($input->getArgument('lookup'), $input->getOption('type'), $this->getContainer());

        // perform resource lookup
        $resource_file = $lookup->getFilePath();

        // edit file
        $edit_prefix = $this->getContainer()->getParameter('liip.code.edit_command');
        $edit_command = escapeshellcmd(sprintf('%s %s', $edit_prefix, $resource_file));
        passthru($edit_command, $return_code);
        return $return_code;
    }
}

