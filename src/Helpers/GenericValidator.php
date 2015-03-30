<?php

namespace Franzip\SerpFetcher\Helpers;

/**
 * Namespace validation methods.
 * @package  SerpFetcher
 */
class GenericValidator
{
    /**
     * Check if content is valid.
     * @param  string $content
     * @param  int    $size
     * @return bool
     */
    static public function invalidContent($content, $size)
    {
        return (empty($content) || strlen($content) > $size);
    }

    /**
     * Perform validation on SerpFetcher constructor arguments.
     * @param  string $cacheDir
     * @param  int    $cacheTTL
     * @param  bool   $caching
     * @param  bool   $cacheForever
     * @param  string $charset
     */
    static public function argsValidation($cacheDir, $cacheTTL, $caching,
                                          $cacheForever, $charset)
    {
        if (!self::validateDirName($cacheDir))
            throw new \Franzip\SerpFetcher\Exceptions\InvalidArgumentException('Invalid SerpFetcher $cacheDir: please supply a valid non-empty string.');

        if (!self::validateExpirationTime($cacheTTL))
            throw new \Franzip\SerpFetcher\Exceptions\InvalidArgumentException('Invalid SerpFetcher $cacheTTL: please supply a positive integer.');

        if (!self::validateCacheOpt($caching))
            throw new \Franzip\SerpFetcher\Exceptions\InvalidArgumentException('Invalid SerpFetcher $caching: please supply a boolean value.');

        if (!self::validateCacheOpt($cacheForever))
            throw new \Franzip\SerpFetcher\Exceptions\InvalidArgumentException('Invalid SerpFetcher $cacheForever: please supply a boolean value.');

        if (!self::validateCharset($charset))
            throw new \Franzip\SerpFetcher\Exceptions\InvalidArgumentException('Invalid SerpFetcher $charset: please supply a valid non-empty string.');
    }

    /**
     * Validate dir option
     * @param  string $dir
     * @return bool
     */
    static public function validateDirName($dir)
    {
        return is_string($dir) && !empty($dir);
    }

    /**
     * Validate cache expiration option.
     * @param  int $hours
     * @return bool
     */
    static public function validateExpirationTime($hours)
    {
        return is_int($hours) && $hours > 0;
    }

    /**
     * Validate caching option.
     * @param  bool $opt
     * @return bool
     */
    static public function validateCacheOpt($opt)
    {
        return is_bool($opt);
    }

    /**
     * Validate charset.
     * @param  string $charset
     * @return bool
     */
    static public function validateCharset($charset)
    {
        return is_string($charset) && !empty($charset);
    }

    private function __construct() {}
}
