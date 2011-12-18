<?php
namespace Liip\CodeBundle\Command;

use Liip\CodeBundle\Model\Lookup;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EditCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('code:edit')
            ->setDescription('Edit the source file of a named resource')
            ->addArgument('lookup', InputArgument::REQUIRED, 'What do you want to edit?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // gather arguments and options
        $lookup = new Lookup($input->getArgument('lookup'), $this->getContainer());

        // perform resource lookup
        $resource_file = $lookup->getFilePath();
        $edit_prefix = $this->getContainer()->getParameter('liip.code.edit_command');
        $edit_command = escapeshellcmd(sprintf('%s %s', $edit_prefix, $resource_file));
        passthru($edit_command, $return_code);
        return $return_code;
    }
}

