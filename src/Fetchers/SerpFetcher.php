<?php

/**
 * SerpFetcher -- Wrapper around SimpleHtmlDom to fetch data from Search Engine result pages with cache support.
 * @version 0.2.0
 * @author Francesco Pezzella <franzpezzella@gmail.com>
 * @link https://github.com/franzip/serp-fetcher
 * @copyright Copyright 2015 Francesco Pezzella
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package SerpFetcher
 */

namespace Franzip\SerpFetcher\Fetchers;
use Franzip\SerpFetcher\Helpers\FileSystemHelper;
use Franzip\SerpFetcher\Helpers\GenericValidator;
use ThauEx\SimpleHtmlDom\SimpleHtmlDom;

/**
 * Abstract class describing a SerpFetcher.
 * All the commonalities are factored out here and implemented directly.
 * Each concrete Fetcher subclass should only be concerned about the way it
 * extracts data from the SimpleHtmlDom wrapper, implementing the following methods:
 * getPageUrls(), getPageTitles(), getPageSnippets().
 * Usage of a different charset (UTF-8 is used as default) has not been
 * implemented and tested yet.
 * @package  SerpFetcher
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

    // default opts to normalize fetched results
    const DEFAULT_RESULT_NUMBER = 10;
    const DEFAULT_PAD_ENTRY     = "PAD";

    // search engine specific methods
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
        // perform validation
        GenericValidator::argsValidation($cacheDir, $cacheTTL, $caching,
                                         $cacheForever, $charset);
        // set up the instance
        $this->cacheDir     = $cacheDir;
        $this->cacheTTL     = $cacheTTL;
        $this->caching      = $caching;
        $this->cacheForever = $cacheForever;
        $this->charset      = $charset;
        // create the cache dir if it isn't there
        FileSystemHelper::setUpDir($cacheDir);
    }

    /**
     * Get a multidimensional array with urls, titles and snippets for a given
     * SERP url.
     * @param  string $url
     * @return array
     */
    public function fetch($url)
    {
        $SHDObject = $this->getSHDWrapper($url);
        $urls      = $this->getPageUrls($SHDObject);
        $titles    = $this->getPageTitles($SHDObject);
        $snippets  = $this->getPageSnippets($SHDObject);
        return array('urls'     => $urls,
                     'titles'   => $titles,
                     'snippets' => $snippets);
    }

    /**
     * Check if cache should be hit for a given url request.
     * @param  string $url
     * @return bool
     */
    public function cacheHit($url) {
        $file = FileSystemHelper::getCachedEntry($url, $this->getCacheDir());
        return $this->isCaching() && FileSystemHelper::cacheEntryExists($file)
               && FileSystemHelper::validateCache($file, $this->getCacheTTL(),
                                                  $this->isCachingForever());
    }

    /**
     * Remove all cached files in the cache folder.
     * The root cache folder will not be removed.
     */
    public function flushCache()
    {
        FileSystemHelper::rrmdir($this->getCacheDir(), false);
    }

    /**
     * Wipe out the cache entirely by recursively removing the cache folder and
     * all its subfolders.
     * Since there won't be a cache folder anymore, this method will also turn
     * caching off.
     */
    public function removeCache()
    {
        FileSystemHelper::rrmdir($this->getCacheDir());
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
        if (GenericValidator::validateDirName($dir)) {
            $this->cacheDir = $dir;
            FileSystemHelper::setUpDir($dir);
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
        if (GenericValidator::validateExpirationTime($hours)) {
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
        if (FileSystemHelper::folderExists($this->getCacheDir())) {
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
     * Turn permanent caching on.
     * Will fail if caching is not active.
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
        if (GenericValidator::validateCharset($charset)) {
            $this->charset = $charset;
            return true;
        }
        return false;
    }

    /**
     * Extract urls from fetched raw html text.
     * Allow injecting the proper regex patterns to clean for a given search engine.
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
        // sanity check
        if (GenericValidator::invalidContent($content, self::DEFAULT_MAX_FILE_SIZE))
            return false;
        // create the wrapper
        $dom = new SimpleHtmlDom(null, self::SHD_USE_INCLUDE_PATH,
                                 self::SHD_FORCE_TAGS_CLOSED,
                                 $this->getCharset(), self::SHD_STRIP_RN,
                                 self::SHD_DEFAULT_BR_TEXT,
                                 self::SHD_DEFAULT_SPAN_TEXT);
        // load the fetched content in the wrapper
        $dom->load($content, self::SHD_LOWERCASE, self::SHD_STRIP_RN);
        return $dom;
    }

    /**
     * Fetch content for a given url as a big string.
     * This is an adaptation of SHD::getContent() method.
     * @param  string $url
     * @return string
     */
    private function fetchSerpContent($url)
    {
        $file = FileSystemHelper::getCachedEntry($url, $this->getCacheDir());
        // check if we have a cache entry
        if ($this->cacheHit($url)) {
            return file_get_contents($file);
        }
        // fetch content
        $content = file_get_contents($url, self::SHD_USE_INCLUDE_PATH,
                                     self::SHD_CONTEXT, self::SHD_OFFSET);
        // add cache signature
        $content .= '<!-- cached: ' . time() . ' -->';
        file_put_contents($file, $content);
        // encode the string properly
        return utf8_encode($content);
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
}
