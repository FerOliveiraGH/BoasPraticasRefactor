<?php
namespace FerOliveira\GoogleCrawler;

use FerOliveira\GoogleCrawler\Exception\InvalidGoogleHtmlException;
use FerOliveira\GoogleCrawler\Exception\InvalidResultException;
use FerOliveira\GoogleCrawler\Proxy\{
    GoogleProxyInterface, NoProxy
};
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\DomCrawler\Link;
use DOMElement;

/**
 * Google Crawler
 *
 * @package FerOliveira\GoogleCrawler
 * @author Fernando Oliveira
 */
class Crawler
{
    /** @var GoogleProxyInterface $proxy */
    protected $proxy;

    public function __construct(
        GoogleProxyInterface $proxy = null
    ) {
        $this->proxy = $proxy ?? new NoProxy();
    }

    /**
     * Returns the 100 first found results for the specified search term
     *
     * @param SearchTermInterface $searchTerm
     * @param string $googleDomain
     * @param string $countryCode
     * @return ResultList
     */
    public function getResults(
        SearchTermInterface $searchTerm,
        string $googleDomain = 'google.com',
        string $countryCode = ''
    ): ResultList {
        if (stripos($googleDomain, 'google.') === false || stripos($googleDomain, 'http') === 0) {
            throw new \InvalidArgumentException('Invalid google domain');
        }

        $googleUrl = "https://$googleDomain/search?q={$searchTerm}&num=100";
        $googleUrl = !empty($countryCode) ? $googleUrl . "&gl={$countryCode}" : $googleUrl;
        $response = $this->proxy->getHttpResponse($googleUrl);
        $stringResponse = (string) $response->getBody();
        $domCrawler = new DomCrawler($stringResponse);
        $googleResultList = $this->createGoogleResultList($domCrawler);

        $resultList = new ResultList($googleResultList->count());

        $domElementParser = new DomElementParser($this->proxy);
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
