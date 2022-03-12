<?php
namespace FerOliveira\GoogleCrawler\Tests\Unit\Proxy;

use FerOliveira\GoogleCrawler\Exception\InvalidResultException;
use FerOliveira\GoogleCrawler\Exception\InvalidUrlException;
use FerOliveira\GoogleCrawler\Proxy\NoProxy;
use PHPUnit\Framework\TestCase;

class NoProxyTest extends TestCase
{
    public function testUrlFromGoogleSuggestionMustThrowInvalidResultException()
    {
        $this->expectException(InvalidResultException::class);
        $noProxy = new NoProxy();
        $invalidUrl = 'http://google.com/search?q=Test&num=100&ie=UTF-8&prmd=ivnsla&source=univ&tbm=nws&tbo=u&sa=X&ved=0ahUKEwiF5PS6w6vSAhWJqFQKHQ_wBDAQqAIIKw';
        $noProxy->parseUrl($invalidUrl);
    }

    public function testUrlMustBeCorrectlyParsed()
    {
        $noProxy = new NoProxy();
        $validUrl = 'http://google.com//url?q=http://www.speedtest.net/pt/&sa=U&ved=0ahUKEwjYuPbkxqvSAhXFQZAKHdpyAxMQFggUMAA&usg=AFQjCNFR74JMZRVu3EUNUUHa7o_1ETZoiQ';
        $url = $noProxy->parseUrl($validUrl);
        static::assertEquals('http://www.speedtest.net/pt/', $url);
    }

    public function testTryingToGetHttpResponseFromInvalidUrlMustThrowException()
    {
        $this->expectException(InvalidUrlException::class);
        $noProxy = new NoProxy();
        $noProxy->getHttpResponse('teste');
    }
}
