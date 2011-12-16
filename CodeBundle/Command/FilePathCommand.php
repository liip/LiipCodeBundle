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
            ->setName('code:path')
            ->setDescription('Returns the absolute file path given template logical name')
            ->addArgument('lookup', InputArgument::REQUIRED, 'What template are you looking for?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // gather arguments
        $lookup = $input->getArgument('lookup');

        // access services
        $container = $this->getContainer();
        $parser = $container->get('templating.name_parser');
        $locator = $container->get('file_locator');

        // template logicalName to symfony path
        $template_reference = $parser->parse($lookup);
        $sf_path = $template_reference->getPath();
        $file_path = $locator->locate($sf_path);

        $output->writeln($file_path);
    }
}
