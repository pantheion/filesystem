<?php

namespace Pantheion\Filesystem;

class Element
{
    /**
     * Returns the full path given a relative path.
     *
     * @param string $path Relative path
     * @return string Full path
     */   
    protected static function fullpath($path)
    {
        return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $path;
    }
}