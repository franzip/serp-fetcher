[![Build Status](https://travis-ci.org/franzip/serp-fetcher.svg?branch=master)](https://travis-ci.org/franzip/serp-fetcher)

# SerpFetcher
Wrapper around SimpleHtmlDom to easily fetch data from Search Engine Result Pages with built-in caching support.

## Installing via Composer (recommended)

Install composer in your project:
```
curl -s http://getcomposer.org/installer | php
```

Create a composer.json file in your project root:
```
{
    "require": {
        "franzip/serp-fetcher": "0.1.*@dev"
    }
}
```

Install via composer
```
php composer.phar install
```

## Supported Search Engines

* Google
* Bing
* Ask
* Yahoo

## Legal Disclaimer

Under no circumstances I shall be considered liable to any user for direct, indirect, incidental, consequential, special, or exemplary damages, arising from or relating to userʹs use or misuse of this software.
Consult the following Terms of Service before using SerpFetcher:

* [Google](https://www.google.com/accounts/TOS)
* [Bing](http://windows.microsoft.com/en-us/windows/microsoft-services-agreement)
* [Ask](http://about.ask.com/terms-of-service)
* [Yahoo](https://info.yahoo.com/legal/us/yahoo/utos/en-us/)

## Description

You can create a SerpFetcher using both the provided Factory or importing the
fetcher you need directly into your namespace.

All the various implementations share a common abstract ancestor class
`SerpFetcher`, and therefore expose five main configurable attributes through
setters:

```php
SerpFetcher($cacheDir = 'cache', $cacheTTL = 24, $caching = true,
            $cachingForever = false, $charset = 'UTF-8')
```

1. `$cacheDir`
    - Path to the folder to use as temporary cache.
    - You can specify an absolute or relative path.
    - If it doesn't exist, the folder will be automatically created on instantiation.
2. `$cacheTTL`
    - The expiration time of the cache, expressed in hours.
3. `$caching`
    - Flag if the object should use caching.
4. `$cacheForever`
    - Flag if the object should use permanent caching (cached pages will never expire).
5. `$charset`
    - Charset to use.
    - Note: **Only UTF-8 (used as default) has been tested so far.**

The main method `fetch()` implemented for each class returns an associative array
with urls, snippets and titles for a given SERP url.
If the array with fetched results has less than 10 entries, padding will be added
to sum up to 10.

## Constructor (using Factory)
Supply the name of the search engine and you are ready to go. It is possible
to pass an optional array with custom arguments.

```php
use Franzip\SerpFetcher\SerpFetcherBuilder;

$googleFetcher = SerpFetcherBuilder::create('Google');
$askFetcher = SerpFetcherBuilder::create('Ask', array($cacheDir = 'foo/bar'));
$bingFetcher = SerpFetcherBuilder::create('Bing', array($cacheDir = 'baz',
                                                        $cacheTTL = 1));
...
```

## Constructor (using Fetchers directly)

```php
use Franzip\SerpFetcher\Fetchers\AskFetcher;
use Franzip\SerpFetcher\Fetchers\BingFetcher;
use Franzip\SerpFetcher\Fetchers\GoogleFetcher;

$googleFetcher = new GoogleFetcher();
$askFetcher = new AskFetcher('foo/bar');
$bingFetcher = new BingFetcher('baz', 1);
...
```

## Basic Usage

```php
use Franzip\SerpFetcher\SerpFetcherBuilder;

$googleFetcher = SerpFetcherBuilder::create('Google');
$urlToFetch = 'http://www.google.com/search?q=foo';
$fetchedResults = $googleFetcher->fetch($urlToFetch);
// doing your things with the results...
```

## cacheHit()
Your code can handle cache hit and cache miss.

```php
use Franzip\SerpFetcher\SerpFetcherBuilder;

$googleFetcher = SerpFetcherBuilder::create('Google');
$urlToFetch = 'http://www.google.com/search?q=foo';
var_dump($googleFetcher->cacheHit($urlToFetch));
// bool(false)
$fetchedResults = $googleFetcher->fetch('http://www.google.com/search?q=foo');
var_dump($googleFetcher->cacheHit($urlToFetch));
// bool(true)

if ($googleFetcher->cacheHit($urlToFetch)) {
    // handle cache hit
} else {
    // handle cache miss
}
```

## flushCache() and removeCache()
Each fetched url get cached as a single file.
You can remove all those files by calling `flushCache()`.
`removeCache()` will also remove the folder used as cache.

```php
use Franzip\SerpFetcher\SerpFetcherBuilder;

$googleFetcher = SerpFetcherBuilder::create('Google');
$urlToFetch = 'http://www.google.com/search?q=foo';
var_dump($googleFetcher->cacheHit($urlToFetch));
// bool(false)
$fetchedResults = $googleFetcher->fetch('http://www.google.com/search?q=foo');
var_dump($googleFetcher->cacheHit($urlToFetch));
// bool(true)
$googleFetcher->flushCache();
var_dump($googleFetcher->cacheHit($urlToFetch));
// bool(false)

```

## Fine Tuning (Setters)

```php
use Franzip\SerpFetcher\SerpFetcherBuilder;

$googleFetcher = SerpFetcherBuilder::create('Google');
// change cache folder to foo/
$googleFetcher->setCacheDir('foo');
// change cache expiration to 2 days
$googleFetcher->setCacheTTL(48);
// enable permanent caching
$googleFetcher->enableCachingForever();
```

## Using multiple cache directories
Just switch between folders with the `setCacheDir()` method

```php
use Franzip\SerpFetcher\SerpFetcherBuilder;

$googleFetcher = SerpFetcherBuilder::create('Google',
                                            array('foo'));
// fetch some stuff... foo/ will be used as cache folder now
...
// fetched results will now be cached in foobar/
$googleFetcher->setCacheDir('foobar');
// switch back to the initial cache folder foo/
$googleFetcher->setCacheDir('foo');

```

## TODOs

- [ ] A decent exceptions system.
- [ ] Support for HHVM
- [ ] Refactoring messy tests.

## License
[MIT](http://opensource.org/licenses/MIT/ "MIT") Public License.
