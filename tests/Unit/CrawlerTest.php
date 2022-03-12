<?php
namespace FerOliveira\GoogleCrawler\Tests\Unit;

use FerOliveira\GoogleCrawler\Crawler;
use FerOliveira\GoogleCrawler\Exception\InvalidGoogleHtmlException;
use FerOliveira\GoogleCrawler\Proxy\HttpClient\GoogleHttpClient;
use FerOliveira\GoogleCrawler\Proxy\NoProxyFactory;
use FerOliveira\GoogleCrawler\Proxy\ProxyFactory;
use FerOliveira\GoogleCrawler\Proxy\UrlParser\GoogleUrlParse;
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
        $crawler = new Crawler(new NoProxyFactory());
        $crawler->getResults(new SearchTerm(''), $domain);
    }

    public function testTryingToInstantiateACrawlerWithoutGoogleOnTheDomainMustFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $crawler = new Crawler(new NoProxyFactory());
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

        $httpClientMock = $this->createMock(GoogleHttpClient::class);
        $httpClientMock->method('getHttpResponse')
            ->willReturn($responseMock);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')
            ->willReturn($streamMock);

        $urlParserMock = $this->createMock(GoogleUrlParse::class);

        $proxyMock = $this->createMock(ProxyFactory::class);
        $proxyMock->method('createUrlParser')
            ->willReturn($urlParserMock);
        $proxyMock->method('createHttpClient')
            ->willReturn($httpClientMock);
        $searchTermMock = $this->createMock(SearchTermInterface::class);
        $searchTermMock
            ->method('__toString')
            ->willReturn('');

        $crawler = new Crawler($proxyMock);
        $crawler->getResults($searchTermMock);
    }
}
