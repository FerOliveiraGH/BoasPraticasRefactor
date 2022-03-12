<?php

namespace FerOliveira\GoogleCrawler\Tests\Feature;

use FerOliveira\GoogleCrawler\Crawler;
use FerOliveira\GoogleCrawler\Exception\InvalidGoogleHtmlException;
use FerOliveira\GoogleCrawler\Proxy\CommonProxyFactory;
use FerOliveira\GoogleCrawler\SearchTerm;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class DefaultCrawlerTest extends AbstractCrawlerTest
{
    public function testSearchResultsWithoutProxy()
    {
        $searchTerm = new SearchTerm('Test');
        $crawler = new Crawler();

        $results = $crawler->getResults($searchTerm);
        $this->checkResults($results);
    }

    /** @dataProvider getCommonEndpoints */
    public function testSearchResultsWithCommonProxy(string $endpoint)
    {
        $proxyFactory = new CommonProxyFactory($endpoint);
        $searchTerm = new SearchTerm('Test');
        $crawler = new Crawler($proxyFactory);
        try {
            $results = $crawler->getResults($searchTerm);

            $this->checkResults($results);
        } catch (ConnectException $exception) {
            static::markTestIncomplete("Timeout error on $endpoint.");
        } catch (ClientException $e) {
            static::markTestIncomplete('Blocked by google "Too Many Requests" error');
        } catch (InvalidGoogleHtmlException $e) {
            static::markTestSkipped($e->getMessage());
        }
    }

    public function getCommonEndpoints(): array
    {
        return [
            ['https://us.hideproxy.me/includes/process.php?action=update'],
            ['https://nl.hideproxy.me/includes/process.php?action=update'],
            ['https://de.hideproxy.me/includes/process.php?action=update']
        ];
    }
}
