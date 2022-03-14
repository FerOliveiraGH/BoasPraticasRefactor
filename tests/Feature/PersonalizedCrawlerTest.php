<?php

namespace FerOliveira\GoogleCrawler\Tests\Feature;

use FerOliveira\GoogleCrawler\Crawler;
use FerOliveira\GoogleCrawler\Proxy\NoProxyFactory;
use FerOliveira\GoogleCrawler\SearchTerm;
use GuzzleHttp\Exception\GuzzleException;

class PersonalizedCrawlerTest extends AbstractCrawlerTest
{
    public function testSearchOnBrazilianGoogleWithoutProxy()
    {
        $searchTerm = new SearchTerm('Test');
        $crawler = new Crawler(new NoProxyFactory());

        $results = $crawler->getResults($searchTerm,'google.com.br', 'BR');
        $this->checkResults($results);
    }

    public function testSearchWithInvalidCountrySuffixMustFail()
    {
        $this->expectException(GuzzleException::class);
        $searchTerm = new SearchTerm('Test');
        $crawler = new Crawler(new NoProxyFactory());

        $crawler->getResults($searchTerm, 'google.ab');
    }
}
