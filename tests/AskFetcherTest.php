<?php

namespace Franzip\SerpFetcher\AskFetcher\Test;
use Franzip\SerpFetcher\Helpers\TestHelper;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class AskFetcherTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        TestHelper::cleanMess();
    }

    public function testFetchingPageUrls()
    {
        $askFetcher = Builder::create('Ask');
        $getPageUrls = TestHelper::getMethod('getPageUrls', 'Ask');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Ask');
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo'));
        $urls = $getPageUrls->invokeArgs($askFetcher, array($SHDObject));
        $this->assertEquals(count($urls), 10);
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo&page=2'));
        $urls = $getPageUrls->invokeArgs($askFetcher, array($SHDObject));
        $this->assertEquals(count($urls), 10);
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $askFetcher->flushCache();
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $askFetcher->removeCache();
    }

    public function testFetchingPageTitles()
    {
        $askFetcher = Builder::create('Ask');
        $getPageTitles = TestHelper::getMethod('getPageTitles', 'Ask');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Ask');
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo'));
        $titles = $getPageTitles->invokeArgs($askFetcher, array($SHDObject));
        $this->assertEquals(count($titles), 10);
        foreach ($titles as $title) {
            $this->assertTrue(is_string($title) && !empty($title));
        }
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo&page=2'));
        $titles = $getPageTitles->invokeArgs($askFetcher, array($SHDObject));
        $this->assertEquals(count($titles), 10);
        foreach ($titles as $title) {
            $this->assertTrue(is_string($title) && !empty($title));
        }
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $askFetcher->flushCache();
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $askFetcher->removeCache();
    }

    public function testFetchingPageSnippets()
    {
        $askFetcher = Builder::create('Ask');
        $getPageSnippets = TestHelper::getMethod('getPageSnippets', 'Ask');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Ask');
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo'));
        $snippets = $getPageSnippets->invokeArgs($askFetcher, array($SHDObject));
        $this->assertEquals(count($snippets), 10);
        foreach ($snippets as $snippet) {
            $this->assertTrue(is_string($snippet) && !empty($snippet));
        }
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo&page=2'));
        $snippets = $getPageSnippets->invokeArgs($askFetcher, array($SHDObject));
        $this->assertEquals(count($snippets), 10);
        foreach ($snippets as $snippet) {
            $this->assertTrue(is_string($snippet) && !empty($snippet));
        }
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $askFetcher->flushCache();
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $askFetcher->removeCache();
    }

    public function testFetchingMainMethod()
    {
        $askFetcher = Builder::create('Ask');
        $results = $askFetcher->fetch('http://us.ask.com/web?q=foo');
        $this->assertTrue(array_key_exists('urls', $results));
        $this->assertTrue(array_key_exists('titles', $results));
        $this->assertTrue(array_key_exists('snippets', $results));
        $this->assertEquals(count($results['urls']), 10);
        $this->assertEquals(count($results['titles']), 10);
        $this->assertEquals(count($results['snippets']), 10);
        $results = $askFetcher->fetch('http://us.ask.com/web?q=foo&page=2');
        $this->assertTrue(array_key_exists('urls', $results));
        $this->assertTrue(array_key_exists('titles', $results));
        $this->assertTrue(array_key_exists('snippets', $results));
        $this->assertEquals(count($results['urls']), 10);
        $this->assertEquals(count($results['titles']), 10);
        $this->assertEquals(count($results['snippets']), 10);
    }
}
