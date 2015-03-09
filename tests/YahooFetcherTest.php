<?php

namespace Franzip\SerpFetcher\YahooFetcher\Test;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class YahooFetcherTest extends PHPUnit_Framework_TestCase
{
    public function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir")
                        $this->rrmdir($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    protected static function getMethod($name, $className) {
        $classQualifiedName = Builder::FETCHER_CLASS_PREFIX . $className . Builder::FETCHER_CLASS_SUFFIX;
        $class = new \ReflectionClass($classQualifiedName);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected function tearDown()
    {
        $dir = new \DirectoryIterator(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
        $dontDelete = array('tests', 'src', 'vendor');
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()
                && !in_array($fileinfo->getFileName(), $dontDelete)) {
                $this->rrmdir($fileinfo->getFilename());
            }
        }
    }

    public function testFetchingPageUrls()
    {
        $yahooFetcher = Builder::create('Yahoo');
        $getPageUrls = self::getMethod('getPageUrls', 'Yahoo');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Yahoo');
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo'));
        $urls = $getPageUrls->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertEquals(count($urls), 10);

        foreach ($urls as $url) {
            $this->assertEquals(1, preg_match('/^(?:https?)/', $url));
        }
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo&b=11'));
        $urls = $getPageUrls->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertEquals(count($urls), 10);
        foreach ($urls as $url) {
            $this->assertEquals(1, preg_match('/^(?:https?)/', $url));
        }
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
        $getPageTitles = self::getMethod('getPageTitles', 'Yahoo');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Yahoo');
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo'));
        $titles = $getPageTitles->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertEquals(count($titles), 10);
        foreach ($titles as $title) {
            $this->assertTrue(is_string($title) && !empty($title));
        }
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo&b=11'));
        $titles = $getPageTitles->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertEquals(count($titles), 10);
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
        $getPageSnippets = self::getMethod('getPageSnippets', 'Yahoo');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Yahoo');
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo'));
        $snippets = $getPageSnippets->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertEquals(count($snippets), 10);
        foreach ($snippets as $snippet) {
            $this->assertTrue(is_string($snippet) && !empty($snippet));
        }
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?p=foo&b=11'));
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?p=foo&b=11'));
        $snippets = $getPageSnippets->invokeArgs($yahooFetcher, array($SHDObject));
        $this->assertEquals(count($snippets), 10);
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
        $this->assertTrue(array_key_exists('urls', $results));
        $this->assertTrue(array_key_exists('titles', $results));
        $this->assertTrue(array_key_exists('snippets', $results));
        $this->assertEquals(count($results['urls']), 10);
        $this->assertEquals(count($results['titles']), 10);
        $this->assertEquals(count($results['snippets']), 10);
        $results = $yahooFetcher->fetch('https://search.yahoo.com/search?p=foo&b=10');
        $this->assertTrue(array_key_exists('urls', $results));
        $this->assertTrue(array_key_exists('titles', $results));
        $this->assertTrue(array_key_exists('snippets', $results));
        $this->assertEquals(count($results['urls']), 10);
        $this->assertEquals(count($results['titles']), 10);
        $this->assertEquals(count($results['snippets']), 10);
    }
}
