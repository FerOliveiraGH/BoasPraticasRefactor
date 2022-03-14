<?php

namespace FerOliveira\GoogleCrawler;

use Ds\Vector;
use Iterator;
use IteratorAggregate;
use IteratorIterator;

class ResultList implements IteratorAggregate
{
    private Vector $results;

    public function __construct(int $capacity = null)
    {
        $this->results = new Vector();

        if (!is_null($capacity)) {
            $this->results->allocate($capacity);
        }
    }

    public function addResult(?Result $result)
    {
        if (empty($result)) {
            return;
        }

        $this->results->push($result);
    }

    public function getResults(): Vector
    {
        return $this->results->copy();
    }

    public function getIterator(): Iterator
    {
        return new IteratorIterator($this->results);
    }
}
