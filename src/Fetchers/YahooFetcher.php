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

namespace Franzip\SerpFetcher\Fetchers;

/**
 * Implements a SerpFetcher for Yahoo search engine.
 * @package  SerpFetcher
 */
class YahooFetcher extends SerpFetcher
{
    /**
     * Get all urls for a given Yahoo SERP page.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageUrls($SHDObject)
    {
        $urls = array();
        foreach($SHDObject->find('.td-u') as $object) {
            $href   = $object->href;
            $urls[] = $this->cleanUrl($href, array('/^\/url\?q=/', '/\/&amp;sa=.*/', '/&amp;sa.*/'));
        }
        // fetch only organic results
        return $this->normalizeResult($urls);
    }

    /**
     * Get all titles for a given Yahoo SERP page.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageTitles($SHDObject)
    {
        $titles = array();
        foreach($SHDObject->find('.compTitle h3.title') as $object) {
            // extract and clean anchors innertext
            $titleText = $object->innertext;
            $titles[]  = $this->cleanText($titleText);
        }
        // fetch only organic results
        return $this->normalizeResult($titles);
    }

    /**
     * Get all snippets for a given Yahoo SERP page.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageSnippets($SHDObject)
    {
        $snippets = array();
        foreach ($SHDObject->find('.aAbs') as $object) {
            $snippetText = $this->cleanText($object->innertext);
            $snippets[]  = $this->fixRepeatedSpace($snippetText);
        }
        // fetch only organic results
        return $this->normalizeResult($snippets);
    }
}
