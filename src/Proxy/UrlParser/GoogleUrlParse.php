<?php

namespace FerOliveira\GoogleCrawler\Proxy\UrlParser;

interface GoogleUrlParse
{
    public function parseUrl(string $googleUrl): string;
}