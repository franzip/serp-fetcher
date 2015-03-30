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
 * Implements a SerpFetcher for Google search engine.
 * @package  SerpFetcher
 */
class GoogleFetcher extends SerpFetcher
{
    /**
     * Get all urls for a given Google SERP page.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageUrls($SHDObject)
    {
        $urls = array();
        // anchors in Google's SERP are direct children of H3 elements with class "r"
        foreach($SHDObject->find('.r a') as $object) {
            $href   = $object->href;
            $urls[] = $this->cleanUrl($href, array('/^\/url\?q=/', '/\/&amp;sa=.*/', '/&amp;sa.*/'));
        }
        // fetch only organic results
        return $this->normalizeResult($urls);
    }

    /**
     * Get all titles for a given Google SERP.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageTitles($SHDObject)
    {
        $titles = array();
        foreach($SHDObject->find('.r a') as $object) {
            // extract and clean anchors innertext
            $titleText = $object->innertext;
            $titles[]  = $this->cleanText($titleText);
        }
        // fetch only organic results
        return $this->normalizeResult($titles);
    }

    /**
     * Get all snippets for a given Google SERP.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageSnippets($SHDObject)
    {
        $snippets = array();
        // snippets in Google's SERP are embedded into a <span> element with class "st"
        foreach ($SHDObject->find('.st') as $object) {
            $snippetText = $this->cleanText($object->innertext);
            $snippets[]  = $this->fixRepeatedSpace($snippetText);
        }
        // fetch only organic results
        return $this->normalizeResult($snippets);
    }
}
