<?php

namespace FerOliveira\GoogleCrawler\Tests\Unit;

use FerOliveira\GoogleCrawler\Exception\InvalidUrlException;
use FerOliveira\GoogleCrawler\Result;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testInvalidUrlMustThrowException()
    {
        $this->expectException(InvalidUrlException::class);
        $result = new Result();
        $result->setUrl('teste');
    }

    public function testValidUrlMustNotThrowException()
    {
        $url = 'https://example.com';
        $result = new Result();
        $result->setUrl($url);

        static::assertEquals($url, $result->getUrl());
    }

    public function testDescriptionMustRemoveNewlineCharsAndTrim()
    {
        $result = new Result();
        $description = <<<EOL
            Test
            description
            with
            newline chars 
            EOL;
        $result->setDescription($description);

        static::assertEquals('Test description with newline chars', $result->getDescription());
    }
}