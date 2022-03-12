<?php

namespace FerOliveira\GoogleCrawler\Proxy\UrlParser;

use FerOliveira\GoogleCrawler\Exception\InvalidResultException;

class NoProxyGoogleUrlParser implements GoogleUrlParse
{
    public function parseUrl(string $googleUrl): string
    {
        $urlParse = parse_url($googleUrl);
        parse_str($urlParse['query'], $queryStringParams);

        $resultUrl = filter_var($queryStringParams['q'], FILTER_VALIDATE_URL);
        if (!$resultUrl) {
            throw new InvalidResultException();
        }

        return $resultUrl;
    }
}