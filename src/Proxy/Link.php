<?php

namespace FerOliveira\GoogleCrawler\Proxy;

use DOMNode;
use Symfony\Component\DomCrawler\Link as DomLink;

class Link extends DomLink
{
    public function __construct(DOMNode $node, string $currentUri = null, ?string $method = 'GET')
    {
        parent::__construct($node, $currentUri, $method);
    }

}