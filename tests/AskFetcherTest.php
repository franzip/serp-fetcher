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
        $this->assertCount(10, $urls);
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo&page=2'));
        $urls = $getPageUrls->invokeArgs($askFetcher, array($SHDObject));
        $this->assertCount(10, $urls);
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
        $this->assertCount(10, $titles);
        foreach ($titles as $title) {
            $this->assertTrue(is_string($title) && !empty($title));
        }
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo&page=2'));
        $titles = $getPageTitles->invokeArgs($askFetcher, array($SHDObject));
        $this->assertCount(10, $titles);
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
        $this->assertCount(10, $snippets);
        foreach ($snippets as $snippet) {
            $this->assertTrue(is_string($snippet) && !empty($snippet));
        }
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo&page=2'));
        $snippets = $getPageSnippets->invokeArgs($askFetcher, array($SHDObject));
        $this->assertCount(10, $snippets);
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
        $this->assertArrayHasKey('urls', $results);
        $this->assertArrayHasKey('titles', $results);
        $this->assertArrayHasKey('snippets', $results);
        $this->assertCount(10, $results['urls']);
        $this->assertCount(10, $results['titles']);
        $this->assertCount(10, $results['snippets']);
        $results = $askFetcher->fetch('http://us.ask.com/web?q=foo&page=2');
        $this->assertArrayHasKey('urls', $results);
        $this->assertArrayHasKey('titles', $results);
        $this->assertArrayHasKey('snippets', $results);
        $this->assertCount(10, $results['urls']);
        $this->assertCount(10, $results['titles']);
        $this->assertCount(10, $results['snippets']);
    }
}
