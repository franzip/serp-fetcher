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
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Unknown or unsupported Search Engine.
     */
    public function testInvalidEngineArgument()
    {
        $fetcher = Builder::create('foobar');
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Something went wrong with the supplied arguments. Check them and try again.
     */
    public function testInvalidAddArgument1()
    {
        $fetcher = Builder::create($this->engines[0], array('foo', 'bar'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Something went wrong with the supplied arguments. Check them and try again.
     */
    public function testInvalidAddArgument2()
    {
        $fetcher = Builder::create($this->engines[1], array('foo', 0));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Something went wrong with the supplied arguments. Check them and try again.
     */
    public function testInvalidAddArgument3()
    {
        $fetcher = Builder::create($this->engines[2], array('foo', 48, 'bar'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Something went wrong with the supplied arguments. Check them and try again.
     */
    public function testInvalidAddArgument4()
    {
        $fetcher = Builder::create($this->engines[3], array('foo', 48, true, 24));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Something went wrong with the supplied arguments. Check them and try again.
     */
    public function testInvalidAddArgument5()
    {
        $fetcher = Builder::create($this->engines[0], array('foo', 48, true, true, 24));
    }

    public function testFetcherTypes()
    {
        $googleFetcher = Builder::create($this->engines[0]);
        $this->assertEquals("Franzip\SerpFetcher\Fetchers\GoogleFetcher", get_class($googleFetcher));
        $askFetcher = Builder::create($this->engines[1]);
        $this->assertEquals("Franzip\SerpFetcher\Fetchers\AskFetcher", get_class($askFetcher));
        $bingFetcher = Builder::create($this->engines[2]);
        $this->assertEquals("Franzip\SerpFetcher\Fetchers\BingFetcher", get_class($bingFetcher));
        $yahooFetcher = Builder::create($this->engines[3]);
        $this->assertEquals("Franzip\SerpFetcher\Fetchers\YahooFetcher", get_class($yahooFetcher));
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
