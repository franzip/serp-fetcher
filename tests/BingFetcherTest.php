<?php

namespace Franzip\SerpFetcher\BingFetcher\Test;
use Franzip\SerpFetcher\Helpers\TestHelper;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class BingFetcherTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        TestHelper::cleanMess();
    }

    public function testFetchingPageUrls()
    {
        $bingFetcher = Builder::create('Bing');
        $getPageUrls = TestHelper::getMethod('getPageUrls', 'Bing');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Bing');
        $SHDObject = $getSHDWrapper->invokeArgs($bingFetcher,
                                                array('http://www.bing.com/search?q=foo'));
        $urls = $getPageUrls->invokeArgs($bingFetcher, array($SHDObject));
        $this->assertEquals(count($urls), 10);
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=foo&first=11'));
        $SHDObject = $getSHDWrapper->invokeArgs($bingFetcher,
                                                array('http://www.bing.com/search?q=foo&first=11'));
        $urls = $getPageUrls->invokeArgs($bingFetcher, array($SHDObject));
        $this->assertEquals(count($urls), 10);
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=foo&first=11'));
        $bingFetcher->flushCache();
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=foo&first=11'));
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $bingFetcher->removeCache();
    }

    public function testFetchingPageTitles()
    {
        $bingFetcher = Builder::create('Bing');
        $getPageTitles = TestHelper::getMethod('getPageTitles', 'Bing');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Bing');
        $SHDObject = $getSHDWrapper->invokeArgs($bingFetcher,
                                                array('http://www.bing.com/search?q=foo'));
        $titles = $getPageTitles->invokeArgs($bingFetcher, array($SHDObject));
        $this->assertEquals(count($titles), 10);
        foreach ($titles as $title) {
            $this->assertTrue(is_string($title) && !empty($title));
        }
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=foo&first=11'));
        $SHDObject = $getSHDWrapper->invokeArgs($bingFetcher,
                                                array('http://www.bing.com/search?q=foo&first=11'));
        $titles = $getPageTitles->invokeArgs($bingFetcher, array($SHDObject));
        $this->assertEquals(count($titles), 10);
        foreach ($titles as $title) {
            $this->assertTrue(is_string($title) && !empty($title));
        }
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=foo&first=11'));
        $bingFetcher->flushCache();
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=foo&first=11'));
        $bingFetcher->removeCache();
    }

    public function testFetchingPageSnippets()
    {
        $bingFetcher = Builder::create('Bing');
        $getPageSnippets = TestHelper::getMethod('getPageSnippets', 'Bing');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Bing');
        $SHDObject = $getSHDWrapper->invokeArgs($bingFetcher,
                                                array('http://www.bing.com/search?q=foo'));
        $snippets = $getPageSnippets->invokeArgs($bingFetcher, array($SHDObject));
        $this->assertEquals(count($snippets), 10);
        foreach ($snippets as $snippet) {
            $this->assertTrue(is_string($snippet) && !empty($snippet));
        }
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=foo&first=11'));
        $SHDObject = $getSHDWrapper->invokeArgs($bingFetcher,
                                                array('http://www.bing.com/search?q=foo&first=11'));
        $snippets = $getPageSnippets->invokeArgs($bingFetcher, array($SHDObject));
        $this->assertEquals(count($snippets), 10);
        foreach ($snippets as $snippet) {
            $this->assertTrue(is_string($snippet) && !empty($snippet));
        }
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=foo&first=11'));
        $bingFetcher->flushCache();
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=foo&first=11'));
        $bingFetcher->removeCache();
    }

    public function testFetchingMainMethod()
    {
        $bingFetcher = Builder::create('Bing');
        $results = $bingFetcher->fetch('http://www.bing.com/search?q=foo');
        $this->assertTrue(array_key_exists('urls', $results));
        $this->assertTrue(array_key_exists('titles', $results));
        $this->assertTrue(array_key_exists('snippets', $results));
        $this->assertEquals(count($results['urls']), 10);
        $this->assertEquals(count($results['titles']), 10);
        $this->assertEquals(count($results['snippets']), 10);
        $results = $bingFetcher->fetch('http://www.bing.com/search?q=foo&first=10');
        $this->assertTrue(array_key_exists('urls', $results));
        $this->assertTrue(array_key_exists('titles', $results));
        $this->assertTrue(array_key_exists('snippets', $results));
        $this->assertEquals(count($results['urls']), 10);
        $this->assertEquals(count($results['titles']), 10);
        $this->assertEquals(count($results['snippets']), 10);
    }
}
