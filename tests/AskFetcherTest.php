<?php

namespace Franzip\SerpFetcher\AskFetcher\Test;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class AskFetcherTest extends PHPUnit_Framework_TestCase
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
        $askFetcher = Builder::create('Ask');
        $getPageUrls = self::getMethod('getPageUrls', 'Ask');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Ask');
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo'));
        $urls = $getPageUrls->invokeArgs($askFetcher, array($SHDObject));
        $this->assertEquals(count($urls), 9);
        foreach ($urls as $url) {
            $this->assertEquals(1, preg_match('/^(?:https?)/', $url));
        }
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo&page=2'));
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo&page=2'));
        $urls = $getPageUrls->invokeArgs($askFetcher, array($SHDObject));
        $this->assertEquals(count($urls), 10);
        foreach ($urls as $url) {
            $this->assertEquals(1, preg_match('/^(?:https?)/', $url));
        }
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
        $getPageTitles = self::getMethod('getPageTitles', 'Ask');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Ask');
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo'));
        $titles = $getPageTitles->invokeArgs($askFetcher, array($SHDObject));
        $this->assertEquals(count($titles), 9);
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
        $getPageSnippets = self::getMethod('getPageSnippets', 'Ask');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Ask');
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo'));
        $snippets = $getPageSnippets->invokeArgs($askFetcher, array($SHDObject));
        $this->assertEquals(count($snippets), 9);
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
        $this->assertEquals(count($results['urls']), 9);
        $this->assertEquals(count($results['titles']), 9);
        $this->assertEquals(count($results['snippets']), 9);
        $results = $askFetcher->fetch('http://us.ask.com/web?q=foo&page=2');
        $this->assertTrue(array_key_exists('urls', $results));
        $this->assertTrue(array_key_exists('titles', $results));
        $this->assertTrue(array_key_exists('snippets', $results));
        $this->assertEquals(count($results['urls']), 10);
        $this->assertEquals(count($results['titles']), 10);
        $this->assertEquals(count($results['snippets']), 10);
    }
}
