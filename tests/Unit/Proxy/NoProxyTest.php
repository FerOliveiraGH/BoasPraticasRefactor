<?php

namespace FerOliveira\GoogleCrawler\Tests\Unit\Proxy;

use FerOliveira\GoogleCrawler\Exception\InvalidResultException;
use FerOliveira\GoogleCrawler\Exception\InvalidUrlException;
use FerOliveira\GoogleCrawler\Proxy\NoProxyFactory;
use PHPUnit\Framework\TestCase;

class NoProxyTest extends TestCase
{
    public function testUrlFromGoogleSuggestionMustThrowInvalidResultException()
    {
        $this->expectException(InvalidResultException::class);
        $noProxyFactory = new NoProxyFactory();
        $noProxy = $noProxyFactory->createUrlParser();
        $invalidUrl = 'http://google.com/search?q=Test&num=100&ie=UTF-8&prmd=ivnsla&source=univ&tbm=nws&tbo=u&sa=X&ve'
            . 'd=0ahUKEwiF5PS6w6vSAhWJqFQKHQ_wBDAQqAIIKw';
        $noProxy->parseUrl($invalidUrl);
    }

    public function testUrlMustBeCorrectlyParsed()
    {
        $noProxyFactory = new NoProxyFactory();
        $noProxy = $noProxyFactory->createUrlParser();
        $validUrl = 'http://google.com//url?q=http://www.speedtest.net/pt/&sa=U&ved=0ahUKEwjYuPbkxqvSAhXFQZAKHdpyAxMQ'
            . 'FggUMAA&usg=AFQjCNFR74JMZRVu3EUNUUHa7o_1ETZoiQ';
        $url = $noProxy->parseUrl($validUrl);
        static::assertEquals('http://www.speedtest.net/pt/', $url);
    }

    public function testTryingToGetHttpResponseFromInvalidUrlMustThrowException()
    {
        $this->expectException(InvalidUrlException::class);
        $noProxyFactory = new NoProxyFactory();
        $noProxy = $noProxyFactory->createHttpClient();
        $noProxy->getHttpResponse('teste');
    }
}
