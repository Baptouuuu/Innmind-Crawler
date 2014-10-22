<?php

namespace Innmind\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Innmind\CrawlerBundle\ResourceRequest;

class CrawlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('crawl')
            ->setDescription('Retrieve html for the given uri')
            ->addArgument(
                'uri',
                InputArgument::REQUIRED,
                'URI to retrieve'
            )
            ->addOption(
                'referer',
                'r',
                InputOption::VALUE_OPTIONAL,
                'Simulate a http referer',
                null
            )
            ->addOption(
                'language',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Specify the wished content language',
                null
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $crawler = $this
            ->getContainer()
            ->get('crawler');

        $request = new ResourceRequest();

        $request->setURI($input->getArgument('uri'));

        if ($input->getOption('referer')) {
            $request->addHeader('Referer', $input->getOption('referer'));
        }

        if ($input->getOption('language')) {
            $request->addHeader('Accept-Language', $input->getOption('language'));
        }

        $resource = $crawler->crawl($request);
    }
}