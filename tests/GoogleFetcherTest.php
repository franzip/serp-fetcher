<?php

namespace Franzip\SerpFetcher\GoogleFetcher\Test;
use Franzip\SerpFetcher\Helpers\TestHelper;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class GoogleFetcherTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        TestHelper::cleanMess();
    }

    public function testFetchingPageUrls()
    {
        $googleFetcher = Builder::create('Google');
        $getPageUrls = TestHelper::getMethod('getPageUrls', 'Google');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Google');
        $SHDObject = $getSHDWrapper->invokeArgs($googleFetcher,
                                                array('http://www.google.com/search?q=foo'));
        $urls = $getPageUrls->invokeArgs($googleFetcher, array($SHDObject));
        $this->assertEquals(count($urls), 10);
        $this->assertTrue($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertFalse($googleFetcher->cacheHit('https://www.google.com/search?q=foo&start=10'));
        $SHDObject = $getSHDWrapper->invokeArgs($googleFetcher,
                                                array('https://www.google.com/search?q=foo&start=10'));
        $urls = $getPageUrls->invokeArgs($googleFetcher, array($SHDObject));
        $this->assertEquals(count($urls), 10);
        $this->assertTrue($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertTrue($googleFetcher->cacheHit('https://www.google.com/search?q=foo&start=10'));
        $googleFetcher->flushCache();
        $this->assertFalse($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertFalse($googleFetcher->cacheHit('https://www.google.com/search?q=foo&start=10'));
        $googleFetcher->removeCache();
    }

    public function testFetchingPageTitles()
    {
        $googleFetcher = Builder::create('Google');
        $getPageTitles = TestHelper::getMethod('getPageTitles', 'Google');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Google');
        $SHDObject = $getSHDWrapper->invokeArgs($googleFetcher,
                                                array('http://www.google.com/search?q=foo'));
        $titles = $getPageTitles->invokeArgs($googleFetcher, array($SHDObject));
        $this->assertEquals(count($titles), 10);
        foreach ($titles as $title) {
            $this->assertTrue(is_string($title) && !empty($title));
        }
        $this->assertTrue($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertFalse($googleFetcher->cacheHit('https://www.google.com/search?q=foo&start=10'));
        $SHDObject = $getSHDWrapper->invokeArgs($googleFetcher,
                                                array('https://www.google.com/search?q=foo&start=10'));
        $titles = $getPageTitles->invokeArgs($googleFetcher, array($SHDObject));
        $this->assertEquals(count($titles), 10);
        foreach ($titles as $title) {
            $this->assertTrue(is_string($title) && !empty($title));
        }
        $this->assertTrue($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertTrue($googleFetcher->cacheHit('https://www.google.com/search?q=foo&start=10'));
        $googleFetcher->flushCache();
        $this->assertFalse($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertFalse($googleFetcher->cacheHit('https://www.google.com/search?q=foo&start=10'));
        $googleFetcher->removeCache();
    }

    public function testFetchingPageSnippets()
    {
        $googleFetcher = Builder::create('Google');
        $getPageSnippets = TestHelper::getMethod('getPageSnippets', 'Google');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Google');
        $SHDObject = $getSHDWrapper->invokeArgs($googleFetcher,
                                                array('http://www.google.com/search?q=foo'));
        $snippets = $getPageSnippets->invokeArgs($googleFetcher, array($SHDObject));
        $this->assertEquals(count($snippets), 10);
        foreach ($snippets as $snippet) {
            $this->assertTrue(is_string($snippet));
        }
        $this->assertTrue($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertFalse($googleFetcher->cacheHit('https://www.google.com/search?q=foo&start=10'));
        $SHDObject = $getSHDWrapper->invokeArgs($googleFetcher,
                                                array('https://www.google.com/search?q=foo&start=10'));
        $snippets = $getPageSnippets->invokeArgs($googleFetcher, array($SHDObject));
        $this->assertEquals(count($snippets), 10);
        foreach ($snippets as $snippet) {
            $this->assertTrue(is_string($snippet));
        }
        $this->assertTrue($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertTrue($googleFetcher->cacheHit('https://www.google.com/search?q=foo&start=10'));
        $googleFetcher->flushCache();
        $this->assertFalse($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertFalse($googleFetcher->cacheHit('https://www.google.com/search?q=foo&start=10'));
        $googleFetcher->removeCache();
    }

    public function testFetchingMainMethod()
    {
        $googleFetcher = Builder::create('Google');
        $results = $googleFetcher->fetch('http://www.google.com/search?q=foo');
        $this->assertTrue(array_key_exists('urls', $results));
        $this->assertTrue(array_key_exists('titles', $results));
        $this->assertTrue(array_key_exists('snippets', $results));
        $this->assertEquals(count($results['urls']), 10);
        $this->assertEquals(count($results['titles']), 10);
        $this->assertEquals(count($results['snippets']), 10);
    }
}
