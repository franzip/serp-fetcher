<?php

namespace Franzip\SerpFetcher\Fetchers;

/**
 * Implements a SerpFetcher for Bing.
 */
class BingFetcher extends SerpFetcher
{
    /**
     * Get a multidimensional array with urls, titles and snippets for a given
     * Bing SERP url.
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
     * Get all urls for a given Bing SERP page.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageUrls($SHDObject)
    {
        $urls = array();
        // anchors in Bing's SERP are in the class "b_algo"
        foreach($SHDObject->find('.b_algo h2 a') as $object) {
            $href   = $object->href;
            $urls[] = $this->cleanUrl($href, array('/^\/url\?q=/', '/\/&amp;sa=.*/', '/&amp;sa.*/'));
        }
        // fetch only organic results
        if (count($urls) > 10) {
            $urls = array_slice($urls, 0, 10);
        }
        return $urls;
    }

    /**
     * Get all titles for a given Bing SERP.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageTitles($SHDObject)
    {
        $titles = array();
        foreach($SHDObject->find('.b_algo h2') as $object) {
            // extract and clean anchors innertext
            $titleText = $object->innertext;
            $titles[]  = $this->cleanText($titleText);
        }
        // fetch only organic results
        if (count($titles) > 10) {
            $titles = array_slice($titles, 0, 10);
        }
        return $titles;
    }

    /**
     * Get all snippets for a given Bing SERP.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageSnippets($SHDObject)
    {
        $snippets = array();
        // snippets in Bing's SERP are embedded into a <p> element child of b_caption
        foreach ($SHDObject->find('.b_caption p') as $object) {
            $snippetText = $this->cleanText($object->innertext);
            $snippets[]  = $this->fixRepeatedSpace($snippetText);
        }
        // fetch only organic results
        if (count($snippets) > 10) {
            $snippets = array_slice($snippets, 0, 10);
        }
        return $snippets;
    }
}
