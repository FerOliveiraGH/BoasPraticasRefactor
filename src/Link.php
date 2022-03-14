<?php

namespace FerOliveira\GoogleCrawler;

use DOMElement;
use DOMException;
use DOMNode;
use Symfony\Component\DomCrawler\Link as DomLink;

class Link extends DomLink
{
    /** @throws DOMException */
    public function __construct(DOMNode $node, string $currentUri = null, ?string $method = 'GET')
    {
        if (!$node instanceof DOMElement) {
            throw new DOMException('Invalid type element.');
        }

        parent::__construct($node, $currentUri, $method);
    }
}