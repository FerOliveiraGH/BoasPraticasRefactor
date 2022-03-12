<?php

namespace FerOliveira\GoogleCrawler;

class SearchTerm implements SearchTermInterface
{
    protected string $searchTerm;

    public function __construct(string $searchTerm)
    {
        $searchTerm = $this->normalize($searchTerm);
        $this->searchTerm = $searchTerm;
    }

    public function __toString(): string
    {
        return $this->searchTerm;
    }

    protected function normalize(string $searchTerm): string
    {
        return rawurlencode($searchTerm);
    }
}
