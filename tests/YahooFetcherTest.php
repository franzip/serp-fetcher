<?php

namespace Franzip\SerpFetcher\YahooFetcher\Test;
use Franzip\SerpFetcher\Helpers\TestHelper;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class YahooFetcherTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        TestHelper::cleanMess();
    }

    public function testFetchingPageUrls()
    {
        $yahooFetcher = Builder::create('Yahoo');
        $getPageUrls = TestHelper::getMethod('getPageUrls', 'Yahoo');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Yahoo');
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo'));
        $urls = $getPageUrls->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertCount(10, $urls);;
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo&b=11'));
        $urls = $getPageUrls->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertCount(10, $urls);;
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $yahooFetcher->flushCache();
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $yahooFetcher->removeCache();
    }

    public function testFetchingPageTitles()
    {
        $yahooFetcher = Builder::create('Yahoo');
        $getPageTitles = TestHelper::getMethod('getPageTitles', 'Yahoo');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Yahoo');
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo'));
        $titles = $getPageTitles->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertCount(10, $titles);;
        foreach ($titles as $title) {
            $this->assertTrue(is_string($title) && !empty($title));
        }
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo&b=11'));
        $titles = $getPageTitles->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertCount(10, $titles);;
        foreach ($titles as $title) {
            $this->assertTrue(is_string($title) && !empty($title));
        }
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $yahooFetcher->flushCache();
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $yahooFetcher->removeCache();
    }

    public function testFetchingPageSnippets()
    {
        $yahooFetcher = Builder::create('Yahoo');
        $getPageSnippets = TestHelper::getMethod('getPageSnippets', 'Yahoo');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Yahoo');
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo'));
        $snippets = $getPageSnippets->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertCount(10, $snippets);;
        foreach ($snippets as $snippet) {
            $this->assertTrue(is_string($snippet) && !empty($snippet));
        }
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo&b=11'));
        $snippets = $getPageSnippets->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertCount(10, $snippets);;
        foreach ($snippets as $snippet) {
            $this->assertTrue(is_string($snippet) && !empty($snippet));
        }
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $yahooFetcher->flushCache();
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $yahooFetcher->removeCache();
    }

    public function testFetchingMainMethod()
    {
        $yahooFetcher = Builder::create('Yahoo');
        $results = $yahooFetcher->fetch('https://search.yahoo.com/search?p=foo');
        $this->assertArrayHasKey('urls', $results);
        $this->assertArrayHasKey('titles', $results);
        $this->assertArrayHasKey('snippets', $results);
        $this->assertCount(10, $results['urls']);;
        $this->assertCount(10, $results['titles']);;
        $this->assertCount(10, $results['snippets']);;
        $results = $yahooFetcher->fetch('https://search.yahoo.com/search?p=foo&b=10');
        $this->assertArrayHasKey('urls', $results);
        $this->assertArrayHasKey('titles', $results);
        $this->assertArrayHasKey('snippets', $results);
        $this->assertCount(10, $results['urls']);;
        $this->assertCount(10, $results['titles']);;
        $this->assertCount(10, $results['snippets']);;
    }
}
