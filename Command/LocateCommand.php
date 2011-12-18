<?php

/*
 * This file is part of the Liip/CodeBundle
 *
 * (c) 2011 Benoit Pointet <benoit.pointet@liip.ch>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Liip\CodeBundle\Command;

use Liip\CodeBundle\Model\Lookup;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LocateCommand extends CodeCommand
{

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('code:locate')
            ->setDescription('Find the filepath of a named resource');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lookup = new Lookup($input->getArgument('lookup'), $input->getOption('type'), $this->getContainer());

        $file_path = $lookup->getFilePath();

        $output->writeln($file_path);
    }

}
