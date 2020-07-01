<?php

namespace Pantheion\Filesystem\Exception;

use Exception;

class DirectoryDoesNotExistException extends Exception
{
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: Directory doesn't exist \n";
    }
}
