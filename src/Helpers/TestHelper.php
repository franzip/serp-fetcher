<?php

namespace Franzip\SerpFetcher\Helpers;
use Franzip\SerpFetcher\SerpFetcherBuilder as Builder;

/**
 * Namespace useful methods used in tests.
 * @package  SerpFetcher
 */
class TestHelper
{
    /**
     * Recursively remove nested dirs and files in $dir by default.
     * @param  string  $dir
     */
    static public function rrmdir($dir)
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
            rmdir($dir);
        }
    }

    /**
     * Clean the filesystem mess created when running tests.
     * Folders to be left untouched are listed in $dontDelete.
     */
    static public function cleanMess()
    {
        $dir = new \DirectoryIterator(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'
                                      . DIRECTORY_SEPARATOR . '..');
        $dontDelete = array('tests', 'src', 'vendor', '.git');
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()
                && !in_array($fileinfo->getFileName(), $dontDelete)) {
                self::rrmdir($fileinfo->getFilename());
            }
        }
    }

    /**
     * Allow testing private methods.
     * @param  string $name
     * @param  string $className
     * @return callable
     */
    static public function getMethod($name, $className) {
        $classQualifiedName = Builder::FETCHER_CLASS_PREFIX . $className . Builder::FETCHER_CLASS_SUFFIX;
        $class = new \ReflectionClass($classQualifiedName);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    private function __constructor() {}
}
