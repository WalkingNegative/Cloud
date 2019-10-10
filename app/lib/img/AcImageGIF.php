<?php

namespace app\lib\img;


use app\lib\img\geometry\UnsupportedFormatException;

class AcImageGIF extends AcImage
{

    public static function isSupport()
    {
        $gdInfo = parent::getGDinfo();
        return $gdInfo['GIF Read Support'] && $gdInfo['GIF Create Support'];
    }

    /**
     * AcImageGIF constructor.
     * @param $filePath
     * @throws UnsupportedFormatException
     * @throws geometry\FileNotFoundException
     * @throws geometry\IllegalArgumentException
     * @throws geometry\InvalidFileException
     */
    protected function __construct($filePath)
    {
        if (!self::isSupport())
            throw new UnsupportedFormatException('gif');

        parent::__construct($filePath);
        $path = parent::getFilePath();
        parent::setResource(@imagecreatefromgif($path));
    }

    /**
     * @param $path
     * @return AcImage
     * @throws UnsupportedFormatException
     * @throws geometry\FileAlreadyExistsException
     * @throws geometry\FileNotSaveException
     */
    public function save($path)
    {
        return parent::saveAsGIF($path);
    }
}
