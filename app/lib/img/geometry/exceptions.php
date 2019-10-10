<?php

namespace app\lib\img\geometry;

class FileNotFoundException extends \Exception
{
    const MESSAGE = 'File not found';

    public function __construct()
    {
        $this->message = self::MESSAGE;
    }
}


class InvalidFileException extends \Exception
{
    public function __construct($path)
    {
        $this->message = "Invalid file: $path";
    }
}

class InvalidChannelException extends \Exception
{

    public function __construct($chenalName)
    {
        $this->message = "Invalid channel: {$chenalName}";
    }
}

class UnsupportedFormatException extends \Exception
{

    public function __construct($format = '')
    {
        $this->message = "This image format ($format) is not supported by your version of GD library";
    }
}

class GDnotInstalledException extends \Exception
{
    public function __construct()
    {
        $this->message = "The GD library is not installed";
    }
}

class FileAlreadyExistsException extends \Exception
{
    public function __construct($path)
    {
        $this->message = "File $path is already exists!";
    }
}

class FileNotSaveException extends \Exception
{
    public function __construct($path)
    {
        $this->message = "File: $path not saved";
    }
}

class IllegalArgumentException extends \Exception
{
    public function __consruct()
    {
        $this->message = "Illegal argument";
    }
}
