<?php

/**
 * SerpFetcher -- Wrapper around SimpleHtmlDom to fetch data from Search Engine result pages with cache support.
 * @version 0.1.0
 * @author Francesco Pezzella <franzpezzella@gmail.com>
 * @link https://github.com/franzip/serp-fetcher
 * @copyright Copyright 2015 Francesco Pezzella
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package SerpFetcher
 */

namespace Franzip\SerpFetcher;

/**
 * SerpFetcher Factory.
 * Return a SerpFetcher object for a given search engine, if implemented.
 * @package  SerpFetcher
 */
class SerpFetcherBuilder
{
    // namespace constants
    const FETCHER_CLASS_PREFIX = '\\Franzip\\SerpFetcher\\Fetchers\\';
    const FETCHER_CLASS_SUFFIX = 'Fetcher';
    // supported implementations
    private static $supportedEngines = array('google', 'yahoo', 'bing', 'ask');

    /**
     * Return a SerpFetcher implementation for a given search engine.
     * @param  string     $engine
     * @param  null|array $args
     * @return mixed
     */
    public static function create($engine, $args = null)
    {
        $engine = strtolower($engine);
        if (self::validEngine($engine)) {
            return (isset($args)) ? self::createWithArgs($engine, $args) : self::createWithArgs($engine, array());
        }
        throw new \InvalidArgumentException('Unknown or unsupported Search Engine.');
    }

    /**
     * Use reflection to instantiate the right Fetcher at runtime.
     * @param  string     $engine
     * @param  null|array $args
     * @return mixed
     */
    private static function createWithArgs($engine, $args)
    {
        $engineName = ucfirst($engine);
        $className = self::FETCHER_CLASS_PREFIX . $engineName . self::FETCHER_CLASS_SUFFIX;
        return call_user_func_array(array(new \ReflectionClass($className), 'newInstance'),
                                    $args);
    }

    /**
     * Check if there is a SerpFetcher implementation for the given search engine.
     * @param  string $engine
     * @return bool
     */
    private static function validEngine($engine)
    {
        return in_array($engine, self::$supportedEngines);
    }

    /**
     * Make the class static.
     */
    private function __construct() {}
}
