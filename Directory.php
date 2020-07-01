<?php

namespace Pantheion\Filesystem;

use Pantheion\Filesystem\Exception\DirectoryAlreadyExistsException;
use Pantheion\Filesystem\Exception\DirectoryDoesNotExistException;

class Directory extends Element
{
    /**
     * Constructs a new Directory instance.
     *
     * @param string $path Path of the directory
     */
    protected function __construct($path)
    {
        $this->resolveDirectory($path);
    }

    /**
     * Fills the instance properties
     *
     * @param string $path Path of the directory
     * @return void
     */
    protected function resolveDirectory($path)
    {
        $pathinfo = pathinfo($path);

        $this->path = str_replace(__DIR__ . DIRECTORY_SEPARATOR, "", $path);
        $this->name = $pathinfo["basename"];
        $this->fullpath = $path;
    }

    /**
     * Returns an array of files contained
     * in the directory
     *
     * @return array
     */
    public function files()
    {
        $files = [];

        foreach (glob($this->path . DIRECTORY_SEPARATOR . "*") as $filename) {
            if(is_file($filename)) {
                $files[] = File::get($filename);
            }
        }

        return $files;
    }

    /**
     * Returns an array of sub folders contained
     * in the directory
     *
     * @return array
     */
    public function children()
    {
        $children = [];

        foreach (glob($this->path . DIRECTORY_SEPARATOR . "*") as $dirname) {
            if (!is_file($dirname)) {
                $children[] = Directory::get($dirname);
            }
        }

        return $children;
    }

    /**
     * Creates a new subfolder in the
     * current Directory instance
     *
     * @param string $name
     * @return Directory new subfolder instance
     */
    public function newChild(string $name)
    {
        return Directory::create($this->path . DIRECTORY_SEPARATOR . $name);
    }

    /**
     * Deletes the directory
     *
     * @return bool
     */
    public function delete()
    {
        return rmdir($this->fullpath);
    }

    /**
     * Creates a new directory
     *
     * @param string $path Path for the new directory.
     * @return Directory New Directory instance.
     */
    public static function create($path)
    {
        $fullpath = Directory::fullpath($path);

        if (Directory::exists($fullpath)) throw new DirectoryAlreadyExistsException();

        mkdir($fullpath);
        return new Directory($fullpath);
    }

    /**
     * Gets a Directory instance for the path.
     *
     * @param string $path Path of the directory
     * @return Directory Directory instance
     */
    public static function get($path)
    {
        $fullpath = Directory::fullpath($path);

        if (!static::exists($fullpath)) throw new DirectoryDoesNotExistException;

        return new Directory($fullpath);
    }

    /**
     * Checks if the path corresponds to an
     * existing directory.
     *
     * @param string $path Path to be checked
     * @return bool 
     */
    public static function exists($path)
    {
        if (strpos($path, __DIR__) !== false) return is_dir($path);

        return is_dir(Directory::fullpath($path));
    }

    /**
     * Deletes the file on the path given
     *
     * @param string $path
     * @return bool
     */
    public static function remove($path)
    {
        if (!static::exists($path)) throw new DirectoryDoesNotExistException;
        
        return rmdir($path);
    }
}
