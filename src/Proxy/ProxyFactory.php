<?php

namespace FerOliveira\GoogleCrawler\Proxy;

use FerOliveira\GoogleCrawler\Proxy\HttpClient\GoogleHttpClient;
use FerOliveira\GoogleCrawler\Proxy\UrlParser\GoogleUrlParse;

interface ProxyFactory
{
    public function __construct(string $endpoint = null);

    public function createHttpClient(): GoogleHttpClient;

    public function createUrlParser(): GoogleUrlParse;
}