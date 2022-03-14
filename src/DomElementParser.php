<?php

namespace FerOliveira\GoogleCrawler;

use DOMElement;
use DOMException;
use DOMNode;
use FerOliveira\GoogleCrawler\Proxy\Link;
use FerOliveira\GoogleCrawler\Proxy\UrlParser\GoogleUrlParse;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class DomElementParser
{
    private GoogleUrlParse $urlParser;

    public function __construct(GoogleUrlParse $urlParser)
    {
        $this->urlParser = $urlParser;
    }

    /** @throws DOMException */
    public function parse(DOMElement $resultDomElement): ?Result
    {
        $resultCrawler = new DomCrawler($resultDomElement);
        $linkElement = $resultCrawler->filterXPath('//a')->getNode(0);
        if (is_null($linkElement)) {
            return null;
        }

        $uri = 'https://google.com/';

        $resultLink = new Link($linkElement, $uri);
        $descriptionElement = $resultCrawler
            ->filterXPath('//div[@class="BNeawe s3v9rd AP7Wnd"]//div[@class="BNeawe s3v9rd AP7Wnd"]')
            ->getNode(0);
        $isImageSuggestion = $resultCrawler->filterXpath('//img')->count() > 0;
        $isNotGoogleUrl = strpos($resultLink->getUri(), 'https://google.com') === false;

        if (
            empty($descriptionElement)
            || $isImageSuggestion
            || $isNotGoogleUrl
        ) {
            return null;
        }

        return $this->createResult($resultLink, $descriptionElement);
    }

    private function createResult(Link $resultLink, DOMNode $descriptionElement): Result
    {
        $description = $descriptionElement->nodeValue
            ?? 'A description for this result isn\'t available due to the robots.txt file.';

        $googleResult = new Result();
        $googleResult
            ->setTitle($resultLink->getNode()->nodeValue)
            ->setUrl($this->urlParser->parseUrl($resultLink->getUri()))
            ->setDescription($description);

        return $googleResult;
    }
}