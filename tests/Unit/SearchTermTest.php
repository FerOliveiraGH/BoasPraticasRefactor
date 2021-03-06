<?php

namespace FerOliveira\GoogleCrawler\Tests\Unit;

use FerOliveira\GoogleCrawler\SearchTerm;
use PHPUnit\Framework\TestCase;

class SearchTermTest extends TestCase
{
    public function testSearchTermShouldNotHaveSpaces()
    {
        $searchTerm = new SearchTerm('Search Term');
        static::assertEquals('Search%20Term', $searchTerm);
    }
}
