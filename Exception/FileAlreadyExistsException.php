<?php

namespace Pantheion\Filesystem\Exception;

use Exception;

class FileAlreadyExistsException extends Exception
{
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: File already exists \n";
    }
}