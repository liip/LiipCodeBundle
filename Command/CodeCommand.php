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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class CodeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addArgument('lookup', InputArgument::REQUIRED, 'Id or name of the resource you are looking for.')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Type of the resource (class|service|template)');
    }
}
