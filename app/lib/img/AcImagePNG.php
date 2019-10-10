<?php

namespace app\lib\img;


use app\lib\img\geometry\UnsupportedFormatException;


class AcImagePNG extends AcImage
{
    public static function isSupport()
    {
        $gdInfo = parent::getGDinfo();
        return (bool)$gdInfo['PNG Support'];
    }

    /**
     * AcImagePNG constructor.
     * @param $filePath
     * @throws UnsupportedFormatException
     * @throws geometry\FileNotFoundException
     * @throws geometry\IllegalArgumentException
     * @throws geometry\InvalidFileException
     */
    protected function __construct($filePath)
    {
        if (!self::isSupport())
            throw new UnsupportedFormatException('png');

        parent::__construct($filePath);
        $path = parent::getFilePath();
        parent::setResource(@imagecreatefrompng($path));
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
        return parent::saveAsPNG($path);
    }

    /**
     * @return float|int
     */
    public static function getQuality()
    {
        return 9 - round(parent::getQuality() / 10);
    }
}
