<?php

namespace Franzip\SerpFetcher\SerpFetcherBuilder\Test;
use Franzip\SerpFetcher\Helpers\TestHelper;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class SerpFetcherBuilderTest extends PHPUnit_Framework_TestCase
{
    protected $engines;

    protected function setUp()
    {
        $engines = array('google', 'ask', 'bing', 'yahoo');
        $this->engines = $engines;
    }

    protected function tearDown()
    {
        TestHelper::cleanMess();
    }

    /**
     * @expectedException        \Franzip\SerpFetcher\Exceptions\UnsupportedEngineException
     * @expectedExceptionMessage Unknown or unsupported Search Engine.
     */
    public function testInvalidEngineArgument()
    {
        $fetcher = Builder::create('foobar');
    }

    /**
     * @expectedException        \Franzip\SerpFetcher\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Invalid SerpFetcher $cacheTTL: please supply a positive integer.
     */
    public function testInvalidAddArgument1()
    {
        $fetcher = Builder::create($this->engines[0], array('foo', 'bar'));
    }

    /**
     * @expectedException        \Franzip\SerpFetcher\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Invalid SerpFetcher $cacheTTL: please supply a positive integer.
     */
    public function testInvalidAddArgument2()
    {
        $fetcher = Builder::create($this->engines[1], array('foo', 0));
    }

    /**
     * @expectedException        \Franzip\SerpFetcher\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Invalid SerpFetcher $caching: please supply a boolean value.
     */
    public function testInvalidAddArgument3()
    {
        $fetcher = Builder::create($this->engines[2], array('foo', 48, 'bar'));
    }

    /**
     * @expectedException        \Franzip\SerpFetcher\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Invalid SerpFetcher $cacheForever: please supply a boolean value.
     */
    public function testInvalidAddArgument4()
    {
        $fetcher = Builder::create($this->engines[3], array('foo', 48, true, 24));
    }

    /**
     * @expectedException        \Franzip\SerpFetcher\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Invalid SerpFetcher $charset: please supply a valid non-empty string.
     */
    public function testInvalidAddArgument5()
    {
        $fetcher = Builder::create($this->engines[0], array('foo', 48, true, true, 24));
    }

    public function testFetcherTypes()
    {
        $googleFetcher = Builder::create($this->engines[0]);
        $this->assertInstanceOf('Franzip\SerpFetcher\Fetchers\GoogleFetcher', $googleFetcher);
        $askFetcher = Builder::create($this->engines[1]);
        $this->assertInstanceOf('Franzip\SerpFetcher\Fetchers\AskFetcher', $askFetcher);
        $bingFetcher = Builder::create($this->engines[2]);
        $this->assertInstanceOf('Franzip\SerpFetcher\Fetchers\BingFetcher', $bingFetcher);
        $yahooFetcher = Builder::create($this->engines[3]);
        $this->assertInstanceOf('Franzip\SerpFetcher\Fetchers\YahooFetcher', $yahooFetcher);
    }

    public function testFactoryWithoutArgs()
    {
        $googleFetcher = Builder::create($this->engines[0]);
        $this->assertEquals($googleFetcher->getCacheDir(), 'cache');
        $this->assertEquals($googleFetcher->getCacheTTL(), 24);
        $this->assertEquals($googleFetcher->getCharset(), 'UTF-8');
        $this->assertTrue($googleFetcher->isCaching());
        $this->assertFalse($googleFetcher->isCachingForever());

        $askFetcher = Builder::create($this->engines[1]);
        $this->assertEquals($askFetcher->getCacheDir(), 'cache');
        $this->assertEquals($askFetcher->getCacheTTL(), 24);
        $this->assertEquals($askFetcher->getCharset(), 'UTF-8');
        $this->assertTrue($askFetcher->isCaching());
        $this->assertFalse($askFetcher->isCachingForever());

        $bingFetcher = Builder::create($this->engines[2]);
        $this->assertEquals($bingFetcher->getCacheDir(), 'cache');
        $this->assertEquals($bingFetcher->getCacheTTL(), 24);
        $this->assertEquals($bingFetcher->getCharset(), 'UTF-8');
        $this->assertTrue($bingFetcher->isCaching());
        $this->assertFalse($bingFetcher->isCachingForever());

        $yahooFetcher = Builder::create($this->engines[3]);
        $this->assertEquals($yahooFetcher->getCacheDir(), 'cache');
        $this->assertEquals($yahooFetcher->getCacheTTL(), 24);
        $this->assertEquals($yahooFetcher->getCharset(), 'UTF-8');
        $this->assertTrue($yahooFetcher->isCaching());
        $this->assertFalse($yahooFetcher->isCachingForever());
    }

    public function testFactoryWithArgs()
    {
        $googleFetcher = Builder::create($this->engines[0],
                                         array('baz', 48, true, true, 'UTF-16'));
        $this->assertEquals($googleFetcher->getCacheDir(), 'baz');
        $this->assertEquals($googleFetcher->getCacheTTL(), 48);
        $this->assertEquals($googleFetcher->getCharset(), 'UTF-16');
        $this->assertTrue($googleFetcher->isCaching());
        $this->assertTrue($googleFetcher->isCachingForever());

        $askFetcher = Builder::create($this->engines[1],
                                      array('bar' . DIRECTORY_SEPARATOR . 'foo',
                                            1, false));
        $this->assertEquals($askFetcher->getCacheDir(), 'bar' . DIRECTORY_SEPARATOR . 'foo');
        $this->assertEquals($askFetcher->getCacheTTL(), 1);
        $this->assertEquals($askFetcher->getCharset(), 'UTF-8');
        $this->assertFalse($askFetcher->isCaching());
        $this->assertFalse($askFetcher->isCachingForever());

        $bingFetcher = Builder::create($this->engines[2],
                                       array('foo'));
        $this->assertEquals($bingFetcher->getCacheDir(), 'foo');
        $this->assertEquals($bingFetcher->getCacheTTL(), 24);
        $this->assertEquals($bingFetcher->getCharset(), 'UTF-8');
        $this->assertTrue($bingFetcher->isCaching());
        $this->assertFalse($bingFetcher->isCachingForever());

        $yahooFetcher = Builder::create($this->engines[3],
                                         array('foo'));
        $this->assertEquals($yahooFetcher->getCacheDir(), 'foo');
        $this->assertEquals($yahooFetcher->getCacheTTL(), 24);
        $this->assertEquals($yahooFetcher->getCharset(), 'UTF-8');
        $this->assertTrue($yahooFetcher->isCaching());
        $this->assertFalse($yahooFetcher->isCachingForever());

    }
}
