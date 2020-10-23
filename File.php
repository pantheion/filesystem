<?php

namespace Pantheion\Filesystem;

use Pantheion\Filesystem\Exception\FileDoesNotExistException;
use Pantheion\Filesystem\Exception\FileAlreadyExistsException;

class File extends Element
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

        $this->path = str_replace($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR, "", $path);
        $this->extension = $pathinfo["extension"];
        $this->name = $pathinfo["filename"];
        $this->fullname = $this->name . "." . $this->extension;
        $this->fullpath = $path;
        $this->size = filesize($path);
    }

    /**
     * Returns the directory where the
     * current file instance is located in
     *
     * @return Directory directory of the file
     */
    public function directory()
    {
        return Directory::get(str_replace(DIRECTORY_SEPARATOR . $this->fullname, "", $this->path));
    }

    /**
     * Deletes the file
     *
     * @return bool
     */
    public function delete()
    {
        return unlink($this->path);
    }

    /**
     * Moves the file to a new directory
     *
     * @param Directory|string $to new directory
     * @return File new file's instance
     */
    public function move($to)
    {
        $newPath = $this->resolveNewPath($to);
        rename($this->fullpath, $newPath);

        return new File($newPath);
    }

    /**
     * Copies the file to a new directory
     *
     * @param Directory|string $to new directory
     * @return File file's copy instance
     */
    public function copy($to)
    {
        $newPath = $this->resolveNewPath($to);
        copy($this->fullpath, $newPath);

        return new File($newPath);
    }

    /**
     * Returns a resolved new path given a
     * new directory
     *
     * @param Directory|string $to
     * @return string
     */
    private function resolveNewPath($to)
    {
        if ($to instanceof Directory) {
            return $to->fullpath . DIRECTORY_SEPARATOR . $this->fullname;
        } else if (is_string($to)) {
            return static::fullpath($to) . DIRECTORY_SEPARATOR . $this->fullname;
        }

        // handle directory does not exist
    }

    /**
     * Renames the file with a new name
     *
     * @param string $newName new name for the file
     * @return File current instance
     */
    public function rename(string $newName)
    {
        $newFullPath = str_replace($this->fullname, $newName, $this->fullpath);
        rename($this->fullpath, $newFullPath);

        $this->resolveFile($newFullPath);
        return $this;
    }

    /**
     * Gets the contents from the file
     * instance
     *
     * @return string
     */
    public function contents()
    {
        return file_get_contents($this->fullpath);
    }

    /**
     * Writes the new contents on the file
     *
     * @param mixed $contents new contents
     * @return File
     */
    public function write($contents)
    {
        file_put_contents($this->fullpath, $contents);
        return $this;
    }

    /**
     * Creates a new file
     *
     * @param string $path Path for the new file.
     * @param $contents File content's to be written.
     * @return File New File instance.
     */
    public static function create(string $path, $contents = null)
    {
        $fullpath = File::fullpath($path);

        if(File::exists($fullpath)) throw new FileAlreadyExistsException;

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

        if (!static::exists($fullpath)) throw new FileDoesNotExistException;

        return new File($fullpath, file_get_contents($fullpath));
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
        if(strpos($path, $_SERVER["DOCUMENT_ROOT"]) !== false) return file_exists($path);

        return file_exists(File::fullpath($path));
    }

    /**
     * Deletes the file on the path given
     *
     * @param string $path
     * @return bool
     */
    public static function remove(string $path)
    {
        if(!static::exists($path)) throw new FileDoesNotExistException;

        unlink($path);
    }
}