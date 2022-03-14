<?php

namespace FerOliveira\GoogleCrawler\Tests\Unit;

use Exception;
use FerOliveira\GoogleCrawler\Result;
use FerOliveira\GoogleCrawler\ResultList;
use PHPUnit\Framework\TestCase;

class ResultListTest extends TestCase
{
    public function testAddResult(): ResultList
    {
        $result = $this->getResultMock();
        $list = new ResultList();
        $list->addResult($result);

        static::assertCount(1, $list->getResults());

        return $list;
    }

    /**
     * @depends testAddResult
     */
    public function testGetResultsMustReturnUnmodifiableList(ResultList $list)
    {
        $results = $list->getResults();
        $results->push($this->getResultMock());

        static::assertCount(1, $list->getResults());
        static::assertCount(2, $results);
    }

    public function testInstantiateResultListWithOrWithoutConstructorParameterMustWork()
    {
        $resultListWithoutCapacity = new ResultList();
        $resultListWithCapacity = new ResultList(1);

        static::assertInstanceOf(ResultList::class, $resultListWithoutCapacity);
        static::assertInstanceOf(ResultList::class, $resultListWithCapacity);
    }

    /** @throws Exception */
    public function testGetIteratorMustReturnAllItems()
    {
        $result1 = $this->getResultMock();
        $result2 = $this->getResultMock();
        $resultList = new ResultList(2);

        $resultList->addResult($result1);
        $resultList->addResult($result2);

        static::assertEquals([$result1, $result2], iterator_to_array($resultList->getIterator()));
    }

    private function getResultMock(): Result
    {
        return $this->createMock(Result::class);
    }
}