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
     * Perform validation on arguments.
     * @param  string $cacheDir
     * @param  int    $cacheTTL
     * @param  bool   $caching
     * @param  bool   $cacheForever
     * @param  string $charset
     * @return bool
     */
    static public function argsValidation($cacheDir, $cacheTTL, $caching,
                                          $cacheForever, $charset)
    {
        return self::validDirName($cacheDir) && self::validExpirationTime($cacheTTL)
               && self::validCacheOpt($caching) && self::validCacheOpt($cacheForever)
               && self::validCharset($charset);
    }

    /**
     * Validate dir option
     * @param  string $dir
     * @return bool
     */
    static public function validDirName($dir)
    {
        return is_string($dir) && !empty($dir);
    }

    /**
     * Validate cache expiration option.
     * @param  int $hours
     * @return bool
     */
    static public function validExpirationTime($hours)
    {
        return is_int($hours) && $hours > 0;
    }

    /**
     * Validate caching option.
     * @param  bool $opt
     * @return bool
     */
    static public function validCacheOpt($opt)
    {
        return is_bool($opt);
    }

    /**
     * Validate charset.
     * @param  string $charset
     * @return bool
     */
    static public function validCharset($charset)
    {
        return is_string($charset) && !empty($charset);
    }

    private function __construct() {}
}
