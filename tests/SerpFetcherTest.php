<?php

namespace Franzip\SerpFetcher\SerpFetcher\Test;
use Franzip\SerpFetcher\Helpers\TestHelper;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class SerpFetcherTest extends PHPUnit_Framework_TestCase
{
    protected $engines;

    protected function setUp()
    {
        $engines   = array('gOOgLe', 'aSk', 'BIng', 'yAHOo');
        $junkFiles = array('junk1', 'junk2', 'junk3', 'junk4');
        $this->engines   = $engines;
        $this->junkFiles = $junkFiles;
    }

    protected function tearDown()
    {
        TestHelper::cleanMess();
    }

    public function testSettersGetters()
    {
        $googleFetcher = Builder::create($this->engines[0]);
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        $this->assertEquals($googleFetcher->getCacheDir(), 'cache');
        $this->assertEquals($googleFetcher->getCacheTTL(), 24);
        $this->assertTrue($googleFetcher->isCaching());
        $this->assertFalse($googleFetcher->isCachingForever());
        $this->assertEquals($googleFetcher->getCharset(), 'UTF-8');
        $this->assertFalse($googleFetcher->setCacheDir(123));
        $this->assertTrue($googleFetcher->setCacheDir('foo' . DIRECTORY_SEPARATOR . 'bar'));
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        $this->assertEquals($googleFetcher->getCacheDir(), 'foo' . DIRECTORY_SEPARATOR . 'bar');
        $this->assertFalse($googleFetcher->setCacheTTL('bar'));
        $this->assertTrue($googleFetcher->setCacheTTL(3));
        $this->assertTrue($googleFetcher->setCharset('UTF-16'));
        $this->assertEquals($googleFetcher->getCacheTTL(), 3);
        $this->assertEquals($googleFetcher->getCharset(), 'UTF-16');
        $this->assertTrue($googleFetcher->isCaching());
        $googleFetcher->disableCaching();
        $this->assertFalse($googleFetcher->isCaching());
        $this->assertFalse($googleFetcher->enableCachingForever());
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        $this->assertTrue($googleFetcher->enableCaching());
        $this->assertTrue($googleFetcher->enableCachingForever());
        $this->assertTrue($googleFetcher->isCachingForever());
        $googleFetcher->disableCachingForever();
        $this->assertFalse($googleFetcher->isCachingForever());

        $askFetcher = Builder::create($this->engines[1]);
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        $this->assertEquals($askFetcher->getCacheDir(), 'cache');
        $this->assertEquals($askFetcher->getCacheTTL(), 24);
        $this->assertTrue($askFetcher->isCaching());
        $this->assertFalse($askFetcher->isCachingForever());
        $this->assertEquals($askFetcher->getCharset(), 'UTF-8');
        $this->assertFalse($askFetcher->setCacheDir(123));
        $this->assertTrue($askFetcher->setCacheDir('foo' . DIRECTORY_SEPARATOR . 'bar'));
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        $this->assertFalse($askFetcher->setCacheTTL('bar'));
        $this->assertTrue($askFetcher->setCacheTTL(3));
        $this->assertTrue($askFetcher->setCharset('UTF-16'));
        $this->assertEquals($askFetcher->getCacheTTL(), 3);
        $this->assertEquals($askFetcher->getCharset(), 'UTF-16');
        $askFetcher->disableCaching();
        $this->assertFalse($askFetcher->enableCachingForever());
        $this->assertTrue($askFetcher->enableCaching());
        $this->assertTrue($askFetcher->enableCachingForever());
        $this->assertTrue($askFetcher->isCachingForever());
        $askFetcher->disableCachingForever();
        $this->assertFalse($askFetcher->isCachingForever());

        $bingFetcher = Builder::create($this->engines[2]);
        $this->assertTrue(file_exists($bingFetcher->getCacheDir()) && is_dir($bingFetcher->getCacheDir()));
        $this->assertEquals($bingFetcher->getCacheDir(), 'cache');
        $this->assertEquals($bingFetcher->getCacheTTL(), 24);
        $this->assertTrue($bingFetcher->isCaching());
        $this->assertFalse($bingFetcher->isCachingForever());
        $this->assertEquals($bingFetcher->getCharset(), 'UTF-8');
        $this->assertFalse($bingFetcher->setCacheDir(123));
        $this->assertTrue($bingFetcher->setCacheDir('foo' . DIRECTORY_SEPARATOR . 'bar'));
        $this->assertTrue(file_exists($bingFetcher->getCacheDir()) && is_dir($bingFetcher->getCacheDir()));
        $this->assertFalse($bingFetcher->setCacheTTL('bar'));
        $this->assertTrue($bingFetcher->setCacheTTL(3));
        $this->assertTrue($bingFetcher->setCharset('UTF-16'));
        $this->assertEquals($bingFetcher->getCacheDir(), 'foo' . DIRECTORY_SEPARATOR . 'bar');
        $this->assertEquals($bingFetcher->getCacheTTL(), 3);
        $this->assertEquals($bingFetcher->getCharset(), 'UTF-16');
        $bingFetcher->disableCaching();
        $this->assertFalse($bingFetcher->enableCachingForever());
        $this->assertTrue($bingFetcher->enableCaching());
        $this->assertTrue($bingFetcher->enableCachingForever());
        $this->assertTrue($bingFetcher->isCachingForever());
        $bingFetcher->disableCachingForever();
        $this->assertFalse($bingFetcher->isCachingForever());

        $yahooFetcher = Builder::create($this->engines[3]);
        $this->assertTrue(file_exists($yahooFetcher->getCacheDir()) && is_dir($yahooFetcher->getCacheDir()));
        $this->assertEquals($yahooFetcher->getCacheDir(), 'cache');
        $this->assertEquals($yahooFetcher->getCacheTTL(), 24);
        $this->assertTrue($yahooFetcher->isCaching());
        $this->assertFalse($yahooFetcher->isCachingForever());
        $this->assertEquals($yahooFetcher->getCharset(), 'UTF-8');
        $this->assertFalse($yahooFetcher->setCacheDir(123));
        $this->assertTrue($yahooFetcher->setCacheDir('foo' . DIRECTORY_SEPARATOR . 'bar'));
        $this->assertTrue(file_exists($yahooFetcher->getCacheDir()) && is_dir($yahooFetcher->getCacheDir()));
        $this->assertFalse($yahooFetcher->setCacheTTL('bar'));
        $this->assertTrue($yahooFetcher->setCacheTTL(3));
        $this->assertTrue($yahooFetcher->setCharset('UTF-16'));
        $this->assertEquals($yahooFetcher->getCacheDir(), 'foo' . DIRECTORY_SEPARATOR . 'bar');
        $this->assertEquals($yahooFetcher->getCacheTTL(), 3);
        $this->assertEquals($yahooFetcher->getCharset(), 'UTF-16');
        $yahooFetcher->disableCaching();
        $this->assertFalse($yahooFetcher->enableCachingForever());
        $this->assertTrue($yahooFetcher->enableCaching());
        $this->assertTrue($yahooFetcher->enableCachingForever());
        $this->assertTrue($yahooFetcher->isCachingForever());
        $yahooFetcher->disableCachingForever();
        $this->assertFalse($yahooFetcher->isCachingForever());
    }

    public function testCacheMethods()
    {
        $googleFetcher = Builder::create($this->engines[0]);
        $this->assertTrue($googleFetcher->isCaching());
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            touch($googleFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file);
        }
        $this->assertTrue($googleFetcher->setCacheDir('bazfoo'));
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            touch($googleFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file);
        }
        $this->assertTrue($googleFetcher->setCacheDir('dumbar'));
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            touch($googleFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file);
        }
        $this->assertTrue($googleFetcher->setCacheDir('cache'));
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            $this->assertTrue(file_exists($googleFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $this->assertTrue($googleFetcher->setCacheDir('bazfoo'));
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            $this->assertTrue(file_exists($googleFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $googleFetcher->flushCache();
        foreach ($this->junkFiles as $file) {
            $this->assertFalse(file_exists($googleFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $this->assertTrue($googleFetcher->setCacheDir('dumbar'));
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            $this->assertTrue(file_exists($googleFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $googleFetcher->flushCache();
        foreach ($this->junkFiles as $file) {
            $this->assertFalse(file_exists($googleFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $this->assertTrue($googleFetcher->setCacheDir('cache'));
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            $this->assertTrue(file_exists($googleFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $googleFetcher->flushCache();
        foreach ($this->junkFiles as $file) {
            $this->assertFalse(file_exists($googleFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $this->assertTrue($googleFetcher->setCacheDir('bazfoo'));
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        $googleFetcher->removeCache();
        $this->assertFalse(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        $this->assertTrue($googleFetcher->setCacheDir('dumbar'));
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        $googleFetcher->removeCache();
        $this->assertFalse(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        $this->assertTrue($googleFetcher->setCacheDir('cache'));
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        $googleFetcher->removeCache();
        $this->assertFalse(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));
        $this->assertFalse($googleFetcher->isCaching());
        $this->assertFalse($googleFetcher->isCachingForever());
        $this->assertFalse($googleFetcher->enableCachingForever());
        $this->assertFalse($googleFetcher->enableCaching());
        $this->assertTrue($googleFetcher->setCacheDir('foo' . DIRECTORY_SEPARATOR . 'bar'));
        $this->assertTrue($googleFetcher->enableCaching());
        $this->assertEquals($googleFetcher->getCacheDir(), 'foo' . DIRECTORY_SEPARATOR . 'bar');
        $this->assertTrue(file_exists($googleFetcher->getCacheDir()) && is_dir($googleFetcher->getCacheDir()));

        $askFetcher = Builder::create($this->engines[1], array('bar', 24, false));
        $this->assertFalse($askFetcher->isCaching());
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        $this->assertTrue($askFetcher->enableCaching());
        foreach ($this->junkFiles as $file) {
            touch($askFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file);
        }
        $this->assertTrue($askFetcher->setCacheDir('bazfoo'));
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            touch($askFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file);
        }
        $this->assertTrue($askFetcher->setCacheDir('dumbar'));
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            touch($askFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file);
        }
        $this->assertTrue($askFetcher->setCacheDir('bar'));
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            $this->assertTrue(file_exists($askFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $this->assertTrue($askFetcher->setCacheDir('bazfoo'));
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            $this->assertTrue(file_exists($askFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $askFetcher->flushCache();
        foreach ($this->junkFiles as $file) {
            $this->assertFalse(file_exists($askFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $this->assertTrue($askFetcher->setCacheDir('dumbar'));
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            $this->assertTrue(file_exists($askFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $askFetcher->flushCache();
        foreach ($this->junkFiles as $file) {
            $this->assertFalse(file_exists($askFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $this->assertTrue($askFetcher->setCacheDir('bar'));
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        foreach ($this->junkFiles as $file) {
            $this->assertTrue(file_exists($askFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $askFetcher->flushCache();
        foreach ($this->junkFiles as $file) {
            $this->assertFalse(file_exists($askFetcher->getCacheDir() . DIRECTORY_SEPARATOR . $file));
        }
        $this->assertTrue($askFetcher->setCacheDir('bazfoo'));
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        $askFetcher->removeCache();
        $this->assertFalse(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        $this->assertTrue($askFetcher->setCacheDir('dumbar'));
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        $askFetcher->removeCache();
        $this->assertFalse(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        $this->assertTrue($askFetcher->setCacheDir('cache'));
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        $askFetcher->removeCache();
        $this->assertFalse(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
        $this->assertFalse($askFetcher->isCaching());
        $this->assertFalse($askFetcher->isCachingForever());
        $this->assertFalse($askFetcher->enableCachingForever());
        $this->assertFalse($askFetcher->enableCaching());
        $this->assertTrue($askFetcher->setCacheDir('foo' . DIRECTORY_SEPARATOR . 'bar'));
        $this->assertTrue($askFetcher->enableCaching());
        $this->assertEquals($askFetcher->getCacheDir(), 'foo' . DIRECTORY_SEPARATOR . 'bar');
        $this->assertTrue(file_exists($askFetcher->getCacheDir()) && is_dir($askFetcher->getCacheDir()));
    }

    public function testFetchingMethods()
    {
        $googleFetcher = Builder::create($this->engines[0]);
        $fetchSerpContent = TestHelper::getMethod('fetchSerpContent', 'Google');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Google');
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=foo"));
        $fetchedContent = $fetchSerpContent->invokeArgs($googleFetcher,
                                                        array('http://www.google.com/search?q=foo'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($googleFetcher->cacheHit("http://www.google.com/search?q=foo"));
        $SHDObject = $getSHDWrapper->invokeArgs($googleFetcher,
                                                array('http://www.google.com/search?q=foo'));
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=bar"));
        $fetchedContent = $fetchSerpContent->invokeArgs($googleFetcher,
                                                        array('http://www.google.com/search?q=bar'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($googleFetcher->cacheHit("http://www.google.com/search?q=bar"));
        $SHDObject = $getSHDWrapper->invokeArgs($googleFetcher,
                                                array('http://www.google.com/search?q=bar'));
        $googleFetcher->disableCaching();
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=foo"));
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=bar"));
        $this->assertTrue($googleFetcher->enableCaching());
        $this->assertTrue($googleFetcher->enableCachingForever());
        $this->assertTrue($googleFetcher->cacheHit("http://www.google.com/search?q=foo"));
        $this->assertTrue($googleFetcher->cacheHit("http://www.google.com/search?q=bar"));
        $this->assertTrue($googleFetcher->setCacheDir('baz'));
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=baz"));
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=foobar"));
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=bar"));
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=foo"));
        $fetchedContent = $fetchSerpContent->invokeArgs($googleFetcher,
                                                        array('http://www.google.com/search?q=baz'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($googleFetcher->cacheHit("http://www.google.com/search?q=baz"));
        $SHDObject = $getSHDWrapper->invokeArgs($googleFetcher,
                                                array('http://www.google.com/search?q=baz'));
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=foobar"));
        $fetchedContent = $fetchSerpContent->invokeArgs($googleFetcher,
                                                        array('http://www.google.com/search?q=foobar'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($googleFetcher->cacheHit("http://www.google.com/search?q=foobar"));
        $SHDObject = $getSHDWrapper->invokeArgs($googleFetcher,
                                                array('http://www.google.com/search?q=foobar'));;
        $this->assertTrue($googleFetcher->setCacheDir('cache'));
        $this->assertTrue($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertTrue($googleFetcher->cacheHit('http://www.google.com/search?q=bar'));
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=baz"));
        $this->assertFalse($googleFetcher->cacheHit("http://www.google.com/search?q=foobar"));
        $this->assertTrue($googleFetcher->setCacheDir('baz'));
        $this->assertFalse($googleFetcher->cacheHit('http://www.google.com/search?q=foo'));
        $this->assertFalse($googleFetcher->cacheHit('http://www.google.com/search?q=bar'));
        $this->assertTrue($googleFetcher->cacheHit("http://www.google.com/search?q=baz"));
        $this->assertTrue($googleFetcher->cacheHit("http://www.google.com/search?q=foobar"));

        $askFetcher = Builder::create($this->engines[1],
                                      array('bar' . DIRECTORY_SEPARATOR . 'foo',
                                            1, false));
        $fetchSerpContent = TestHelper::getMethod('fetchSerpContent', 'Ask');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Ask');

        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=foo"));
        $fetchedContent = $fetchSerpContent->invokeArgs($askFetcher,
                                                        array('http://us.ask.com/web?q=foo'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=foo"));
        $this->assertTrue($askFetcher->enableCaching());
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foo'));
        $this->assertTrue($askFetcher->cacheHit("http://us.ask.com/web?q=foo"));

        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=bar"));
        $fetchedContent = $fetchSerpContent->invokeArgs($askFetcher,
                                                        array('http://us.ask.com/web?q=bar'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($askFetcher->cacheHit("http://us.ask.com/web?q=bar"));
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=bar'));
        $askFetcher->disableCaching();
        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=foo"));
        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=bar"));
        $this->assertTrue($askFetcher->enableCaching());
        $this->assertTrue($askFetcher->enableCachingForever());
        $this->assertTrue($askFetcher->cacheHit("http://us.ask.com/web?q=foo"));
        $this->assertTrue($askFetcher->cacheHit("http://us.ask.com/web?q=bar"));
        $this->assertTrue($askFetcher->setCacheDir('barfoo'));
        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=foo"));
        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=bar"));
        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=foobar"));
        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=baz"));
        $fetchedContent = $fetchSerpContent->invokeArgs($askFetcher,
                                                        array('http://us.ask.com/web?q=baz'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($askFetcher->cacheHit("http://us.ask.com/web?q=baz"));
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=baz'));
        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=foobar"));
        $fetchedContent = $fetchSerpContent->invokeArgs($askFetcher,
                                                        array('http://us.ask.com/web?q=foobar'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($askFetcher->cacheHit("http://us.ask.com/web?q=foobar"));
        $SHDObject = $getSHDWrapper->invokeArgs($askFetcher,
                                                array('http://us.ask.com/web?q=foobar'));
        $this->assertTrue($askFetcher->setCacheDir('bar' . DIRECTORY_SEPARATOR . 'foo'));
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertTrue($askFetcher->cacheHit('http://us.ask.com/web?q=bar'));
        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=baz"));
        $this->assertFalse($askFetcher->cacheHit("http://us.ask.com/web?q=foobar"));
        $this->assertTrue($askFetcher->setCacheDir('barfoo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=foo'));
        $this->assertFalse($askFetcher->cacheHit('http://us.ask.com/web?q=bar'));
        $this->assertTrue($askFetcher->cacheHit("http://us.ask.com/web?q=baz"));
        $this->assertTrue($askFetcher->cacheHit("http://us.ask.com/web?q=foobar"));

        $bingFetcher = Builder::create($this->engines[2],
                                       array('foobar', 48, true, true, 'UTF-16'));
        $fetchSerpContent = TestHelper::getMethod('fetchSerpContent', 'Bing');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Bing');

        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=foo"));
        $fetchedContent = $fetchSerpContent->invokeArgs($bingFetcher,
                                                        array('http://www.bing.com/search?q=foo'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($bingFetcher->cacheHit("http://www.bing.com/search?q=foo"));
        $SHDObject = $getSHDWrapper->invokeArgs($bingFetcher,
                                                array('http://www.bing.com/search?q=foo'));
        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=bar"));
        $fetchedContent = $fetchSerpContent->invokeArgs($bingFetcher,
                                                        array('http://www.bing.com/search?q=bar'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($bingFetcher->cacheHit("http://www.bing.com/search?q=bar"));
        $SHDObject = $getSHDWrapper->invokeArgs($bingFetcher,
                                                array('http://www.bing.com/search?q=bar'));
        $bingFetcher->disableCaching();
        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=foo"));
        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=bar"));
        $this->assertTrue($bingFetcher->enableCaching());
        $this->assertTrue($bingFetcher->enableCachingForever());
        $this->assertTrue($bingFetcher->cacheHit("http://www.bing.com/search?q=foo"));
        $this->assertTrue($bingFetcher->cacheHit("http://www.bing.com/search?q=bar"));
        $this->assertTrue($bingFetcher->setCacheDir('barz'));
        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=baz"));
        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=foobar"));
        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=bar"));
        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=foo"));
        $fetchedContent = $fetchSerpContent->invokeArgs($bingFetcher,
                                                        array('http://www.bing.com/search?q=baz'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($bingFetcher->cacheHit("http://www.bing.com/search?q=baz"));
        $SHDObject = $getSHDWrapper->invokeArgs($bingFetcher,
                                                array('http://www.bing.com/search?q=baz'));
        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=foobar"));
        $fetchedContent = $fetchSerpContent->invokeArgs($bingFetcher,
                                                        array('http://www.bing.com/search?q=foobar'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($bingFetcher->cacheHit("http://www.bing.com/search?q=foobar"));
        $SHDObject = $getSHDWrapper->invokeArgs($bingFetcher,
                                                array('http://www.bing.com/search?q=foobar'));
        $this->assertTrue($bingFetcher->setCacheDir('foobar'));
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $this->assertTrue($bingFetcher->cacheHit('http://www.bing.com/search?q=bar'));
        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=baz"));
        $this->assertFalse($bingFetcher->cacheHit("http://www.bing.com/search?q=foobar"));
        $this->assertTrue($bingFetcher->setCacheDir('barz'));
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=foo'));
        $this->assertFalse($bingFetcher->cacheHit('http://www.bing.com/search?q=bar'));
        $this->assertTrue($bingFetcher->cacheHit("http://www.bing.com/search?q=baz"));
        $this->assertTrue($bingFetcher->cacheHit("http://www.bing.com/search?q=foobar"));

        $yahooFetcher = Builder::create($this->engines[3],
                                        array('fubar', 48, true, true, 'UTF-16'));
        $fetchSerpContent = TestHelper::getMethod('fetchSerpContent', 'Yahoo');
        $getSHDWrapper = TestHelper::getMethod('getSHDWrapper', 'Yahoo');
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=foo"));
        $fetchedContent = $fetchSerpContent->invokeArgs($yahooFetcher,
                                                        array('https://search.yahoo.com/search?q=foo'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=foo"));
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?q=foo'));
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=bar"));
        $fetchedContent = $fetchSerpContent->invokeArgs($yahooFetcher,
                                                        array('https://search.yahoo.com/search?q=bar'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=bar"));
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?q=bar'));
        $yahooFetcher->disableCaching();
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=foo"));
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=bar"));
        $this->assertTrue($yahooFetcher->enableCaching());
        $this->assertTrue($yahooFetcher->enableCachingForever());
        $this->assertTrue($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=foo"));
        $this->assertTrue($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=bar"));
        $this->assertTrue($yahooFetcher->setCacheDir('fubarz'));
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=baz"));
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=foobar"));
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=bar"));
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=foo"));
        $fetchedContent = $fetchSerpContent->invokeArgs($yahooFetcher,
                                                        array('https://search.yahoo.com/search?q=baz'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=baz"));
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?q=baz'));
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=foobar"));
        $fetchedContent = $fetchSerpContent->invokeArgs($yahooFetcher,
                                                        array('https://search.yahoo.com/search?q=foobar'));
        $this->assertRegExp('/^<!doctype html/i', $fetchedContent);
        $this->assertTrue($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=foobar"));
        $SHDObject = $getSHDWrapper->invokeArgs($yahooFetcher,
                                                array('https://search.yahoo.com/search?q=foobar'));
        $this->assertTrue($yahooFetcher->setCacheDir('fubar'));
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?q=foo'));
        $this->assertTrue($yahooFetcher->cacheHit('https://search.yahoo.com/search?q=bar'));
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=baz"));
        $this->assertFalse($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=foobar"));
        $this->assertTrue($yahooFetcher->setCacheDir('fubarz'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?q=foo'));
        $this->assertFalse($yahooFetcher->cacheHit('https://search.yahoo.com/search?q=bar'));
        $this->assertTrue($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=baz"));
        $this->assertTrue($yahooFetcher->cacheHit("https://search.yahoo.com/search?q=foobar"));
    }
}

