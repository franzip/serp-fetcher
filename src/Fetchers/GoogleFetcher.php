<?php

namespace Franzip\SerpFetcher\Fetchers;

/**
 * Implements a SerpFetcher for Google.
 */
class GoogleFetcher extends SerpFetcher
{
    /**
     * Get a multidimensional array with urls, titles and snippets for a given
     * Google SERP url.
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
        $urls = $this->normalizeResult($urls);
        return $urls;
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
        $titles = $this->normalizeResult($titles);
        return $titles;
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
        $snippets = $this->normalizeResult($snippets);
        return $snippets;
    }
}
