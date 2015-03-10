<?php

namespace Franzip\SerpFetcher\Fetchers;
use ThauEx\SimpleHtmlDom\SimpleHtmlDom;

/**
 * Abstract class describing a SerpFetcher.
 * All the commonalities are factored out here and implemented directly.
 * Each concrete Fetcher subclass should only be concerned about the way it
 * extracts data from the SimpleHtmlDom wrapper, implementing the following methods:
 * getPageUrls(), getPageTitles(), getPageSnippets(), fetch().
 * Usage of a different charset (UTF-8 is used as default) has not been
 * implemented and tested yet.
 */
abstract class SerpFetcher
{
    // instance variables

    // folder used to store cached results
    private $cacheDir;
    // cache expiration time in hours
    private $cacheTTL;
    // flag to turn caching
    private $caching;
    // flag to turn permanent caching
    private $cacheForever;
    // charset used
    private $charset;

    // caching default opts
    const DEFAULT_CACHE_FOLDER    = 'cache';
    const DEFAULT_CACHE_TTL       = 24;
    const DEFAULT_CACHING         = true;
    const DEFAULT_CACHING_FOREVER = false;
    const DEFAULT_TARGET_CHARSET  = 'UTF-8';
    const DEFAULT_MAX_FILE_SIZE   = 600000;

    // default opts for SimpleHtmlDom object
    const SHD_USE_INCLUDE_PATH  = false;
    const SHD_CONTEXT           = null;
    const SHD_OFFSET            = -1;
    const SHD_MAXLEN            = -1;
    const SHD_LOWERCASE         = false;
    const SHD_FORCE_TAGS_CLOSED = true;
    const SHD_STRIP_RN          = true;
    const SHD_DEFAULT_BR_TEXT   = "\r\n";
    const SHD_DEFAULT_SPAN_TEXT = " ";

    const DEFAULT_RESULT_NUMBER = 10;
    const DEFAULT_PAD_ENTRY     = "PAD";

    // Search engine specific methods.
    abstract public    function fetch($url);
    abstract protected function getPageUrls($SHDObject);
    abstract protected function getPageTitles($SHDObject);
    abstract protected function getPageSnippets($SHDObject);

    /**
     * Instantiate a SerpFetcher. This constructor is used as is by all concrete
     * implementations.
     * @param string $cacheDir
     * @param int    $cacheTTL
     * @param bool   $caching
     * @param bool   $cacheForever
     * @param string $charset
     */
    public function __construct($cacheDir     = self::DEFAULT_CACHE_FOLDER,
                                $cacheTTL     = self::DEFAULT_CACHE_TTL,
                                $caching      = self::DEFAULT_CACHING,
                                $cacheForever = self::DEFAULT_CACHING_FOREVER,
                                $charset      = self::DEFAULT_TARGET_CHARSET)
    {
        if (self::argsValidation($cacheDir, $cacheTTL, $caching, $cacheForever,
                                 $charset)) {
            $this->cacheDir     = $cacheDir;
            $this->cacheTTL     = $cacheTTL;
            $this->caching      = $caching;
            $this->cacheForever = $cacheForever;
            $this->charset      = $charset;
            $this->setUpCache($cacheDir);
        } else {
            throw new \InvalidArgumentException("Something went wrong with the supplied arguments. Check them and try again.");
        }
    }

    /**
     * Check if cache should be hit for a given url request.
     * @param  string $url
     * @return bool
     */
    public function cacheHit($url) {
        $file = $this->getCachedEntry($url);
        return $this->isCaching() && $this->cacheEntryExists($file)
            && $this->validCache($file);
    }

    /**
     * Remove all cached files in the cache folder.
     * The root cache folder will not be removed.
     */
    public function flushCache()
    {
        $this->rrmdir($this->cacheDir, false);
    }

    /**
     * Wipe out the cache entirely by recursively removing the cache folder and
     * all its subfolders.
     * Since there won't be a cache folder anymore, this method will also turn
     * caching off.
     */
    public function removeCache()
    {
        $this->rrmdir($this->getCacheDir());
        $this->disableCaching();
    }

    /**
     * Get the path to the cache folder.
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * Set the path to the cache folder.
     * @param  string $dir
     * @return bool
     */
    public function setCacheDir($dir)
    {
        if (self::validCacheDir($dir)) {
            $this->cacheDir = $dir;
            $this->setUpCache($dir);
            return true;
        }
        return false;
    }

    /**
     * Get the cache expiration time expressed in hours.
     * @return int
     */
    public function getCacheTTL()
    {
        return $this->cacheTTL;
    }

    /**
     * Set the cache duration expressed in hours.
     * @param  int $hours
     * @return bool
     */
    public function setCacheTTL($hours)
    {
        if (self::validCacheTTL($hours)) {
            $this->cacheTTL = $hours;
            return true;
        }
        return false;
    }

    /**
     * Check if caching is active.
     * @return bool
     */
    public function isCaching()
    {
        return $this->caching;
    }

    /**
     * Turn caching on.
     * Prevent turning caching on if the cache folder hasn't been yet created.
     * @return bool
     */
    public function enableCaching()
    {
        if (self::folderExists($this->getCacheDir())) {
            $this->caching = true;
            return true;
        }
        return false;
    }

    /**
     * Turn caching off. Turn permanent caching off aswell.
     */
    public function disableCaching()
    {
        $this->caching = false;
        $this->disableCachingForever();
    }

    /**
     * Check if permanent caching is active.
     * @return bool
     */
    public function isCachingForever()
    {
        return $this->cacheForever;
    }

    /**
     * Turn permanent caching on. Will fail if caching is turned off.
     * Will not work if caching is not active.
     * @return bool
     */
    public function enableCachingForever()
    {
        if ($this->isCaching()) {
            $this->cacheForever = true;
            return true;
        }
        return false;
    }

    /**
     * Turn permanent caching off.
     */
    public function disableCachingForever()
    {
        $this->cacheForever = false;
    }

    /**
     * Get the charset used by the SimpleHtmlDom object.
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Set the charset used by the SimpleHtmlDom object.
     * @param  string $charset
     * @return bool
     */
    public function setCharset($charset)
    {
        if (self::validCharset($charset)) {
            $this->charset = $charset;
            return true;
        }
        return false;
    }

    /**
     * Extract urls from fetched raw html text.
     * Allow injecting the proper regex patterns to clean for each search engine.
     * @param  string $url
     * @param  array  $patterns
     * @return string
     */
    protected function cleanUrl($url, $patterns)
    {
        return urldecode(preg_replace($patterns, '', $url));
    }

    /**
     * Extract textual data from fetched raw html text.
     * @param  string $text
     * @return string
     */
    protected function cleanText($text)
    {
        $strippedText = strip_tags($text);
        return html_entity_decode($strippedText, ENT_QUOTES, $this->getCharset());
    }

    /**
     * Replace multiple spaces.
     * @param  string $text
     * @return string
     */
    protected function fixRepeatedSpace($text)
    {
        return preg_replace("/\s{2,}/u", " ", $text);
    }

    /**
     * Wrap the fetched content in a SimpleHtmlDom object.
     * This is just an adaptation of SHD::fileGetHtml() method.
     * @param  string $url
     * @return SimpleHtmlDom|false
     */
    protected function getSHDWrapper($url)
    {
        $content = $this->fetchSerpContent($url);

        if ($this->invalidContent($content))
            return false;

        $dom = new SimpleHtmlDom(null, self::SHD_USE_INCLUDE_PATH,
                                 self::SHD_FORCE_TAGS_CLOSED,
                                 $this->getCharset(), self::SHD_STRIP_RN,
                                 self::SHD_DEFAULT_BR_TEXT,
                                 self::SHD_DEFAULT_SPAN_TEXT);

        $dom->load($content, self::SHD_LOWERCASE, self::SHD_STRIP_RN);
        return $dom;
    }

    /**
     * Normalize result array by slicing it or by adding padding.
     * @param  array $resultArray
     * @return array
     */
    protected function normalizeResult($resultArray)
    {
        $countArr = count($resultArray);
        if ($countArr > 10) {
            $resultArray = array_slice($resultArray, 0, 10);
        }
        else if ($countArr < 10) {
            for ($i = $countArr; $i < self::DEFAULT_RESULT_NUMBER; $i++)
                $resultArray[$i] = self::DEFAULT_PAD_ENTRY;
        }
        return $resultArray;
    }

    /**
     * Fetch content for a given url as a big string.
     * This is an adaptation of SHD::getContent() method.
     * @param  string $url
     * @return string
     */
    private function fetchSerpContent($url)
    {
        $file = $this->getCachedEntry($url);

        if ($this->cacheHit($url)) {
            return file_get_contents($file);
        }

        $content = file_get_contents($url, self::SHD_USE_INCLUDE_PATH,
                                     self::SHD_CONTEXT, self::SHD_OFFSET);

        $content .= '<!-- cached: ' . time() . ' -->';
        file_put_contents($file, $content);

        return utf8_encode($content);
    }

    /**
     * Check if fetched content is valid.
     * @param  string $content
     * @return bool
     */
    private function invalidContent($content)
    {
        return (empty($content) || strlen($content) > self::DEFAULT_MAX_FILE_SIZE);
    }

    /**
     * Check if a given cache entry is still valid.
     * Will always return true if permanent caching has been set on.
     * @param  string $file
     * @return bool
     */
    private function validCache($file)
    {
        $currentTime = time();
        $expireTime  = $this->getCacheTTL() * 60 * 60;
        $fileTime    = filemtime($file);
        return ($currentTime - $expireTime < $fileTime)
                or $this->isCachingForever();
    }

    /**
     * Get the path to the cached file for the given url.
     * Each url will generate a unique hash that will be used as filename.
     * @param  string $url
     * @return string
     */
    private function getCachedEntry($url)
    {
        return $this->getCacheDir() . DIRECTORY_SEPARATOR . md5($url);
    }

    /**
     * Check if there exists a cached result for the given filename.
     * @param  string $file
     * @return bool
     */
    private function cacheEntryExists($file)
    {
        return file_exists($file);
    }

    /**
     * Create the cache folder if it isn't there yet.
     * @param string $dir
     */
    private function setUpCache($dir)
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
    private function folderExists($dir)
    {
        return file_exists($dir) && is_dir($dir);
    }

    /**
     * Recursively remove nested dirs and files in $dir by default.
     * If $removeDirs is false, only files will be removed.
     * @param  string  $dir
     * @param  bool    $removeDirs
     */
    private function rrmdir($dir, $removeDirs = true)
    {
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
            if ($removeDirs)
                rmdir($dir);
        }
    }

    /**
     * Perform validation on __construct call.
     * @param  string $cacheDir
     * @param  int    $cacheTTL
     * @param  bool   $caching
     * @param  bool   $cacheForever
     * @param  string $charset
     * @return bool
     */
    static private function argsValidation($cacheDir, $cacheTTL, $caching,
                                           $cacheForever, $charset)
    {
        return self::validCacheDir($cacheDir) && self::validCacheTTL($cacheTTL)
               && self::validCacheOpt($caching) && self::validCacheOpt($cacheForever)
               && self::validCharset($charset);
    }

    /**
     * Validate Cache dir option
     * @param  string $dir
     * @return bool
     */
    static private function validCacheDir($dir)
    {
        return is_string($dir) && !empty($dir);
    }

    /**
     * Validate Cache expiration option
     * @param  int $hours
     * @return bool
     */
    static private function validCacheTTL($hours)
    {
        return is_int($hours) && $hours > 0;
    }

    /**
     * Validate Caching options
     * @param  bool $opt
     * @return bool
     */
    static private function validCacheOpt($opt)
    {
        return is_bool($opt);
    }

    /**
     * Validate Charset
     * @param  string $charset
     * @return bool
     */
    static private function validCharset($charset)
    {
        return is_string($charset) && !empty($charset);
    }
}
