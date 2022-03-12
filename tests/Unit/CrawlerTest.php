<?php
namespace FerOliveira\GoogleCrawler\Tests\Unit;

use FerOliveira\GoogleCrawler\Crawler;
use FerOliveira\GoogleCrawler\Exception\InvalidGoogleHtmlException;
use FerOliveira\GoogleCrawler\Proxy\GoogleProxyInterface;
use FerOliveira\GoogleCrawler\Proxy\NoProxy;
use FerOliveira\GoogleCrawler\SearchTerm;
use FerOliveira\GoogleCrawler\SearchTermInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CrawlerTest extends TestCase
{
    public function testTryingToGetResultsWithHttpOnGoogleDomainMustFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $domain = 'http://google.com';
        $crawler = new Crawler(new NoProxy());
        $crawler->getResults(new SearchTerm(''), $domain);
    }

    public function testTryingToInstantiateACrawlerWithoutGoogleOnTheDomainMustFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $crawler = new Crawler(new NoProxy());
        $crawler->getResults(new SearchTerm(''), 'invalid-domain');
    }

    public function testTryingToParseInvalidHtmlMustThrowException()
    {
        $this->expectException(InvalidGoogleHtmlException::class);
        $streamMock = $this->createMock(StreamInterface::class);
        $streamMock->method('__toString')
            ->willReturn('<html><head></head><body>Invalid HTML</body></html>');

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')
            ->willReturn($streamMock);

        $proxyMock = $this->createMock(GoogleProxyInterface::class);
        $proxyMock->method('getHttpResponse')
            ->willReturn($responseMock);
        $searchTermMock = $this->createMock(SearchTermInterface::class);
        $searchTermMock
            ->method('__toString')
            ->willReturn('');

        $crawler = new Crawler($proxyMock);
        $crawler->getResults($searchTermMock);
    }
}
