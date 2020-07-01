<?php

namespace Pantheion\Filesystem\Exception;

use Exception;

class DirectoryAlreadyExistsException extends Exception
{
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: Directory already exists \n";
    }
}
