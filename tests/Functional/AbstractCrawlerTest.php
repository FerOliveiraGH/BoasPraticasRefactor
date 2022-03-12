<?php
namespace FerOliveira\GoogleCrawler\Tests\Functional;

use FerOliveira\GoogleCrawler\Result;
use FerOliveira\GoogleCrawler\ResultList;
use PHPUnit\Framework\TestCase;

abstract class AbstractCrawlerTest extends TestCase
{
    /**
     * Check if there are results and if they are valid
     *
     * @param ResultList $results
     */
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
