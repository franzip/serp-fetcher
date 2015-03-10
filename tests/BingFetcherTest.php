<?php

namespace Franzip\SerpFetcher\BingFetcher\Test;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class BingFetcherTest extends PHPUnit_Framework_TestCase
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
        $bingFetcher = Builder::create('Bing');
        $getPageUrls = self::getMethod('getPageUrls', 'Bing');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Bing');
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
        $getPageTitles = self::getMethod('getPageTitles', 'Bing');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Bing');
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
        $getPageSnippets = self::getMethod('getPageSnippets', 'Bing');
        $getSHDWrapper = self::getMethod('getSHDWrapper', 'Bing');
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
