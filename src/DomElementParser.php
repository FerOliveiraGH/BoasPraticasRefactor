<?php

namespace FerOliveira\GoogleCrawler;

use DOMElement;
use FerOliveira\GoogleCrawler\Exception\InvalidResultException;
use FerOliveira\GoogleCrawler\Proxy\GoogleProxyInterface;
use FerOliveira\GoogleCrawler\Proxy\NoProxy;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\DomCrawler\Link;

class DomElementParser
{
    private ?GoogleProxyInterface $proxy;

    public function __construct(GoogleProxyInterface $proxy = null)
    {
        $this->proxy = $proxy ?? new NoProxy();
    }

    public function parse(DOMElement $resultDomElement): Result
    {
        $resultCrawler = new DomCrawler($resultDomElement);
        $linkElement = $resultCrawler->filterXPath('//a')->getNode(0);
        if (is_null($linkElement)) {
            throw new InvalidResultException('Link element not found');
        }

        $resultLink = new Link($linkElement, 'http://google.com/');
        $descriptionElement = $resultCrawler->filterXPath('//div[@class="BNeawe s3v9rd AP7Wnd"]//div[@class="BNeawe s3v9rd AP7Wnd"]')->getNode(0);

        if (is_null($descriptionElement)) {
            throw new InvalidResultException('Description element not found');
        }

        $isImageSuggestion = $resultCrawler->filterXpath('//img')->count() > 0;
        if ($isImageSuggestion) {
            throw new InvalidResultException('Result is an image suggestion');
        }

        if (strpos($resultLink->getUri(), 'http://google.com') === false) {
            throw new InvalidResultException('Result is a google suggestion');
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
            ->setUrl($this->proxy->parseUrl($resultLink->getUri()))
            ->setDescription($description);

        return $googleResult;
    }
}