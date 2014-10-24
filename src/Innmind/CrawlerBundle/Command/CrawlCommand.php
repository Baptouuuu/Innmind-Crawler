<?php

namespace Innmind\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Innmind\CrawlerBundle\ResourceRequest;
use Innmind\CrawlerBundle\Entity\HtmlPage;

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

        $output->writeln(sprintf(
            'URI: <fg=cyan>%s</fg=cyan>',
            $resource->getURI()
        ));

        $resource
            ->getHeaders()
            ->forAll(function ($key, $value) use ($output) {
                $output->writeln(sprintf(
                    'Header: <fg=cyan>%s: %s</fg=cyan>',
                    $key,
                    is_array($value) ? implode(', ', $value) : $value
                ));
                return true;
            });

        if ($resource instanceof HtmlPage) {
            $output->writeln(sprintf(
                'Title: <fg=cyan>%s</fg=cyan>',
                $resource->getTitle()
            ));
            $output->writeln(sprintf(
                'Author: <fg=cyan>%s</fg=cyan>',
                $resource->getAuthor()
            ));
            $output->writeln(sprintf(
                'Language: <fg=cyan>%s</fg=cyan>',
                $resource->getLanguage()
            ));
            $output->writeln(sprintf(
                'Description: <fg=cyan>%s</fg=cyan>',
                $resource->getDescription()
            ));
            $output->writeln(sprintf(
                'Canonical: <fg=cyan>%s</fg=cyan>',
                $resource->getCanonical()
            ));
            $output->writeln(sprintf(
                'RSS: <fg=cyan>%s</fg=cyan>',
                $resource->getRSS()
            ));
            $resource
                ->getAlternates()
                ->forAll(function ($lang, $url) use ($output) {
                    $output->writeln(sprintf(
                        'Alternate: <fg=cyan>%s: %s</fg=cyan>',
                        $lang,
                        $url
                    ));
                    return true;
                });
            $output->writeln(sprintf(
                'Has a webapp: <fg=cyan>%s</fg=cyan>',
                $resource->hasWebApp() ? 'true' : 'false'
            ));
            $resource
                ->getLinks()
                ->forAll(function ($key, $url) use ($output) {
                    $output->writeln(sprintf(
                        'Link: <fg=cyan>%s</fg=cyan>',
                        $url
                    ));
                    return true;
                });
        }
    }
}