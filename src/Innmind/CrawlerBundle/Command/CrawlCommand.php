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
            )
            ->addOption(
                'publisher',
                'p',
                InputOption::VALUE_OPTIONAL,
                'URI where to send a POST request with the processed resource',
                null
            )
            ->addOption(
                'token',
                't',
                InputOption::VALUE_OPTIONAL,
                'Token to authentify the resource (required when publisher is set)',
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

        if ($input->getOption('publisher')) {
            $request
                ->setPublisherURI($input->getOption('publisher'))
                ->setToken($input->getOption('token'));
        }

        $resource = $crawler->crawl($request);

        if ($output->getVerbosity() === OutputInterface::VERBOSITY_QUIET) {
            return;
        }

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
        $output->writeln(sprintf(
            'Charset: <fg=cyan>%s</fg=cyan>',
            $resource->getCharset()
        ));

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