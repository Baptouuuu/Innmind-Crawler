<?php

namespace Innmind\CrawlerBundle;

use Pdp\PublicSuffixListManager;
use Pdp\Parser;

class DomainParserFactory
{
    public function make()
    {
        $manager = new PublicSuffixListManager();
        return new Parser($manager->getList());
    }
}
