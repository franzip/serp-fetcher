<?php

namespace Franzip\SerpFetcher\Fetchers;

/**
 * Implements a SerpFetcher for Ask search engine.
 */
class AskFetcher extends SerpFetcher
{
    /**
     * Get all urls for a given Ask SERP page.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageUrls($SHDObject)
    {
        $urls = array();
        // anchors in Ask's SERP are in the class "txt_lg"
        foreach($SHDObject->find('.txt_lg') as $object) {
            $href   = $object->href;
            $urls[] = $this->cleanUrl($href, array('/^\/url\?q=/', '/\/&amp;sa=.*/', '/&amp;sa.*/'));
        }
        // fetch only organic results
        return $this->normalizeResult($urls);
    }

    /**
     * Get all titles for a given Ask SERP page.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageTitles($SHDObject)
    {
        $titles = array();
        foreach($SHDObject->find('.txt_lg') as $object) {
            // extract and clean anchors innertext
            $titleText = $object->innertext;
            $titles[]  = $this->cleanText($titleText);
        }
        // fetch only organic results
        return $this->normalizeResult($titles);
    }

    /**
     * Get all snippets for a given Ask SERP page.
     * @param  SimpleHtmlDom $SHDObject
     * @return array
     */
    protected function getPageSnippets($SHDObject)
    {
        $snippets = array();
        // snippets in Ask's SERP are embedded into a <span> element with class "abstract"
        foreach ($SHDObject->find('.abstract') as $object) {
            $snippetText = $this->cleanText($object->innertext);
            $snippets[]  = $this->fixRepeatedSpace($snippetText);
        }
        // fetch only organic results
        return $this->normalizeResult($snippets);
    }
}
