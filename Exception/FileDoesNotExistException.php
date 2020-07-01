<?php

namespace Pantheion\Filesystem\Exception;

use Exception;

class FileDoesNotExistException extends Exception
{
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: File doesn't exist \n";
    }
}
