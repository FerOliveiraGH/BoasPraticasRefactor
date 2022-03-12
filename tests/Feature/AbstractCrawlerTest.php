<?php

namespace FerOliveira\GoogleCrawler\Tests\Feature;

use FerOliveira\GoogleCrawler\Result;
use FerOliveira\GoogleCrawler\ResultList;
use PHPUnit\Framework\TestCase;

abstract class AbstractCrawlerTest extends TestCase
{
    public function checkResults(ResultList $results): void
    {
        static::assertNotEmpty($results->getResults());

        /** @var Result $result */
        foreach ($results as $result) {
            static::assertInstanceOf(Result::class, $result);
            static::assertNotFalse(filter_var($result->getUrl(), FILTER_VALIDATE_URL));
            static::assertNotEmpty($result->getTitle());
            static::assertNotNull($result->getDescription());
        }
    }
}
