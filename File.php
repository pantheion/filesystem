<?php

namespace Pantheion\Filesystem;

use Pantheion\Filesystem\Exception\FileExistsException;

class File 
{
    /**
     * Constructs a new File instance.
     *
     * @param string $path Path of the file
     * @param [type] $contents
     */
    protected function __construct(string $path)
    {
        $this->resolveFile($path);
    }

    /**
     * Fills the instance properties with the
     * pathinfo output and sets the size property also.
     *
     * @param string $path Path of the file.
     * @return void
     */
    protected function resolveFile(string $path)
    {
        $pathinfo = pathinfo($path);

        $this->path = str_replace(__DIR__ . DIRECTORY_SEPARATOR, "", $path);
        $this->extension = $pathinfo["extension"];
        $this->name = $pathinfo["filename"];
        $this->fullpath = $path;
        $this->size = filesize($path);
    }

    public function directory()
    {
        // get file's directory
    }

    public function delete()
    {

    }

    public function move(Directory $to)
    {

    }

    public function copy(Directory $to)
    {

    }

    public function rename(string $newName)
    {

    }

    public function contents()
    {

    }

    public function write($contents)
    {

    }

    public function siblings()
    {

    }

    /**
     * Creates a new file
     *
     * @param string $path Path for the new file.
     * @param [type] $contents File content's to be written.
     * @return File New File instance.
     */
    public static function create(string $path, $contents = null)
    {
        $fullpath = File::fullpath($path);

        if(File::exists($fullpath)) throw new FileExistsException;

        if (is_null($contents)) 
        {
            touch($fullpath);
            return new File($fullpath);
        }

        file_put_contents($fullpath, $contents);
        return new File($fullpath);
    }

    /**
     * Gets a File instance for the path.
     *
     * @param string $path Path of the file
     * @return File File instance
     */
    public static function get(string $path)
    {
        $fullpath = File::fullpath($path);

        $contents = file_get_contents($fullpath);
        return new File($fullpath, $contents);
    }

    /**
     * Checks if the path corresponds to an
     * existing file.
     *
     * @param string $path Path to be checked
     * @return bool 
     */
    public static function exists(string $path)
    {  
        if(strpos($path, __DIR__) !== false) return file_exists($path);

        return file_exists(File::fullpath($path));
    }

    /**
     * Returns the full path given a relative path.
     *
     * @param string $path Relative path
     * @return string Full path
     */
    public static function fullpath(string $path)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . $path;
    }
}