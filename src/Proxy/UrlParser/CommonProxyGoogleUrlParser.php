<?php

namespace FerOliveira\GoogleCrawler\Proxy\UrlParser;

use FerOliveira\GoogleCrawler\Exception\InvalidResultException;

class CommonProxyGoogleUrlParser implements GoogleUrlParse
{
    public function parseUrl(string $googleUrl): string
    {
        $link = parse_url($googleUrl);
        parse_str($link['query'], $link);

        parse_str($link['u'], $link);
        $link = array_values($link);

        $googleUrl = filter_var($link[0], FILTER_VALIDATE_URL);
        // If this is not a valid URL, so the result is (probably) an image, news or video suggestion
        if (!$googleUrl) {
            throw new InvalidResultException();
        }

        return $googleUrl;
    }
}