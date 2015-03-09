<?php

namespace Franzip\SerpFetcher\Fetchers;

/**
 * Implements a SerpFetcher for Yahoo.
 */
class YahooFetcher extends SerpFetcher
{
    /**
     * Get a multidimensional array with urls, titles and snippets for a given
     * Yahoo SERP url.
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
        if (count($urls) > 10) {
            $urls = array_slice($urls, 0, 10);
        }
        return $urls;
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
        if (count($titles) > 10) {
            $titles = array_slice($titles, 0, 10);
        }
        return $titles;
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
        if (count($snippets) > 10) {
            $snippets = array_slice($snippets, 0, 10);
        }
        return $snippets;
    }
}
