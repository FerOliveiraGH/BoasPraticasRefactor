<?php

namespace FerOliveira\GoogleCrawler;

use FerOliveira\GoogleCrawler\Exception\InvalidGoogleHtmlException;
use FerOliveira\GoogleCrawler\Proxy\HttpClient\GoogleHttpClient;
use FerOliveira\GoogleCrawler\Proxy\NoProxyFactory;
use FerOliveira\GoogleCrawler\Proxy\ProxyFactory;
use FerOliveira\GoogleCrawler\Proxy\UrlParser\GoogleUrlParse;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    private GoogleHttpClient $httpClient;
    private GoogleUrlParse $urlParser;

    public function __construct(
        ProxyFactory $factory = null
    ) {
        $factory = $factory ?? new NoProxyFactory();

        $this->httpClient = $factory->createHttpClient();
        $this->urlParser = $factory->createUrlParser();
    }

    public function getResults(
        SearchTermInterface $searchTerm,
        string $googleDomain = 'google.com',
        string $countryCode = ''
    ): ResultList {
        if (stripos($googleDomain, 'google.') === false || stripos($googleDomain, 'http') === 0) {
            throw new InvalidArgumentException('Invalid google domain');
        }

        $googleUrl = "https://$googleDomain/search?q=$searchTerm&num=100";
        $googleUrl = !empty($countryCode) ? $googleUrl . "&gl=$countryCode" : $googleUrl;
        $response = $this->httpClient->getHttpResponse($googleUrl);
        $stringResponse = (string) $response->getBody();
        $domCrawler = new DomCrawler($stringResponse);
        $googleResultList = $this->createGoogleResultList($domCrawler);

        $resultList = new ResultList($googleResultList->count());

        $domElementParser = new DomElementParser($this->urlParser);
        foreach ($googleResultList as $googleResultElement) {
            $parsedResult = $domElementParser->parse($googleResultElement);
            $resultList->addResult($parsedResult);
        }

        return $resultList;
    }

    private function createGoogleResultList(DomCrawler $domCrawler): DomCrawler
    {
        $googleResultList = $domCrawler->filterXPath('//div[@class="ZINbbc luh4tb xpd O9g5cc uUPGi"]');
        if ($googleResultList->count() === 0) {
            throw new InvalidGoogleHtmlException('No parseable element found');
        }

        return $googleResultList;
    }
}
