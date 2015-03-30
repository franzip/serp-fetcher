<?php

namespace Franzip\SerpFetcher\Helpers;

/**
 * Namespace filesystem related methods.
 * @package  SerpFetcher
 */
class FileSystemHelper
{
    /**
     * Check if a given cache entry is still valid.
     * Will always return true if permanent caching has been set on.
     * @param  string $file
     * @param  int    $cacheTTL
     * @param  bool   $cacheForever
     * @return bool
     */
    static public function validateCache($file, $cacheTTL, $cacheForever)
    {
        $currentTime = time();
        $expireTime  = $cacheTTL * 60 * 60;
        $fileTime    = filemtime($file);
        return ($currentTime - $expireTime < $fileTime)
                or $cacheForever;
    }

    /**
     * Get the path to the cached file for the given url.
     * Each url will generate a unique hash that will be used as filename.
     * @param  string $url
     * @param  string $cacheDir
     * @return string
     */
    static public function getCachedEntry($url, $cacheDir)
    {
        return $cacheDir . DIRECTORY_SEPARATOR . md5($url);
    }

    /**
     * Check if there exists a cached result for the given filename.
     * @param  string $file
     * @return bool
     */
    static public function cacheEntryExists($file)
    {
        return file_exists($file);
    }

    /**
     * Create a folder if it isn't there yet.
     * @param string $dir
     */
    static public function setUpDir($dir)
    {
        if (!self::folderExists($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    /**
     * Check if a folder exists.
     * @param  string $dir
     * @return bool
     */
    static public function folderExists($dir)
    {
        return file_exists($dir) && is_dir($dir);
    }

    /**
     * Recursively remove nested dirs and files in $dir by default.
     * If $removeDirs is false, only files will be removed.
     * @param  string  $dir
     * @param  bool    $removeDirs
     */
    static public function rrmdir($dir, $removeDirs = true)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir")
                        self::rrmdir($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            reset($objects);
            if ($removeDirs)
                rmdir($dir);
        }
    }

    private function __constructor() {}
}
