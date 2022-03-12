<?php

namespace FerOliveira\GoogleCrawler;

use DOMElement;
use FerOliveira\GoogleCrawler\Exception\InvalidResultException;
use FerOliveira\GoogleCrawler\Proxy\UrlParser\GoogleUrlParse;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\DomCrawler\Link;

class DomElementParser
{
    private GoogleUrlParse $urlParser;

    public function __construct(GoogleUrlParse $urlParser)
    {
        $this->urlParser = $urlParser;
    }

    public function parse(DOMElement $resultDomElement): ?Result
    {
        $resultCrawler = new DomCrawler($resultDomElement);
        $linkElement = $resultCrawler->filterXPath('//a')->getNode(0);
        if (is_null($linkElement)) {
            return null;
        }

        $uri = 'http://google.com/';

        $resultLink = new Link($linkElement, $uri);
        $descriptionElement = $resultCrawler
            ->filterXPath('//div[@class="BNeawe s3v9rd AP7Wnd"]//div[@class="BNeawe s3v9rd AP7Wnd"]')
            ->getNode(0);
        $isImageSuggestion = $resultCrawler->filterXpath('//img')->count() > 0;
        $isNotGoogleUrl = strpos($resultLink->getUri(), 'http://google.com') === false;

        if (
            empty($descriptionElement)
            || $isImageSuggestion
            || $isNotGoogleUrl
        ) {
            return null;
        }

        return $this->createResult($resultLink, $descriptionElement);
    }

    /**
     * If $resultLink is a valid link, this method assembles the Result and adds it to $googleResults
     *
     * @param Link $resultLink
     * @param DOMElement $descriptionElement
     * @return Result
     * @throws InvalidResultException
     */
    private function createResult(Link $resultLink, DOMElement $descriptionElement): Result
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