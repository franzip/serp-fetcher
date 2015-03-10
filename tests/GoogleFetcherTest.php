<?php

namespace Franzip\SerpFetcher\GoogleFetcher\Test;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class GoogleFetcherTest extends PHPUnit_Framework_TestCase
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
        $googleFetcher = Builder::create('Google');
        $getPageUrls = self::getMethod('getPageUrls', 'Google');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Google');
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
        $getPageTitles = self::getMethod('getPageTitles', 'Google');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Google');
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
        $getPageSnippets = self::getMethod('getPageSnippets', 'Google');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Google');
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
