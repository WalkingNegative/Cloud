<?php

namespace app\lib\img;

use app\lib\img\geometry\UnsupportedFormatException;


class AcImageJPG extends AcImage
{
    public static function isSupport()
    {
        $gdInfo = parent::getGDinfo();
        $phpVersion = AcImage::getShortPHPVersion();

        if ((float)$phpVersion < 5.3) {
            return (bool)$gdInfo['JPG Support'];
        }

        return (bool)$gdInfo['JPEG Support'];
    }

    /**
     * AcImageJPG constructor.
     * @param $filePath
     * @throws UnsupportedFormatException
     * @throws geometry\FileNotFoundException
     * @throws geometry\IllegalArgumentException
     * @throws geometry\InvalidFileException
     */
    protected function __construct($filePath)
    {
        if (!self::isSupport())
            throw new UnsupportedFormatException('jpeg');

        parent::__construct($filePath);
        $path = parent::getFilePath();
        parent::setResource(@imagecreatefromjpeg($path));
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
        return parent::saveAsJPG($path);
    }
}
