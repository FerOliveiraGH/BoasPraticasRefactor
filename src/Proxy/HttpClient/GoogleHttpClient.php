<?php

namespace FerOliveira\GoogleCrawler\Proxy\HttpClient;

use Psr\Http\Message\ResponseInterface;

interface GoogleHttpClient
{
    public function __construct(string $endpoint = null);

    public function getHttpResponse(string $url): ResponseInterface;
}