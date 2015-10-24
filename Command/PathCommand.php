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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PathCommand extends CodeCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('code:path')
            ->setDescription('Get the symfony path for a named resource');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lookup = new Lookup($input->getArgument('lookup'), $input->getOption('type'), $this->getContainer());

        // perform resource lookup
        $resource_path = $lookup->getPath();

        $output->writeln($resource_path);
    }
}
