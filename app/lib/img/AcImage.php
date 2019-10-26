<?php

namespace app\lib\img;

use app\lib\img\geometry\FileAlreadyExistsException;
use app\lib\img\geometry\FileNotFoundException;
use app\lib\img\geometry\FileNotSaveException;
use app\lib\img\geometry\GDnotInstalledException;
use app\lib\img\geometry\IllegalArgumentException;
use app\lib\img\geometry\InvalidFileException;
use app\lib\img\geometry\Point;
use app\lib\img\geometry\Rectangle;
use app\lib\img\geometry\Size;
use app\lib\img\geometry\UnsupportedFormatException;


class AcImage
{
    const PNG = 'image/png';
    const JPEG = 'image/jpeg';
    const GIF = 'image/gif';

    const PROPORTION = 'pr';
    const PIXELS = 'px';
    const PERCENT = '%';

    const TOP_LEFT = 0;
    const TOP_RIGHT = 1;
    const BOTTOM_RIGHT = 2;
    const BOTTOM_LEFT = 3;

    private static $correctCorners = array(0, 1, 2, 3);
    private static $cornerLogo = 2; // BOTTOM_RIGHT
    private $filePath;
    private $size;
    private $imageInfo;
    private $resource;
    private static $backgroundColor = AcColor::WHITE;
    private static $quality = 85;
    private static $transparency = true;
    private static $gdInfo;
    private static $rewrite = false;
    private static $maxProportionLogo = 0.1;
    private static $paddingProportionLogo = 0.02;

    /**
     * AcImage constructor.
     * @param $filePath
     * @throws FileNotFoundException
     * @throws IllegalArgumentException
     * @throws InvalidFileException
     */
    protected function __construct($filePath)
    {
        if (!self::isFileExists($filePath))
            throw new FileNotFoundException();

        $this->filePath = $filePath;

        $imageInfo = $this->getImageInfo();
        if (!is_array($imageInfo))
            throw new InvalidFileException($filePath);

        $this->setSize(new Size($imageInfo[0], $imageInfo[1]));
    }

    /**
     * @param $filePath
     * @return AcImageGIF|AcImageJPG|AcImagePNG
     * @throws FileNotFoundException
     * @throws GDnotInstalledException
     * @throws IllegalArgumentException
     * @throws InvalidFileException
     * @throws UnsupportedFormatException
     */
    public static function createImage($filePath)
    {
        $image = new AcImage($filePath);

        if (!self::isSupportedGD())
            throw new GDnotInstalledException();

        $imageInfo = $image->getImageInfo();
        if (!is_array($imageInfo))
            throw new InvalidFileException($filePath);

        $mimeType = $imageInfo['mime'];

        switch ($mimeType) {
            case self::JPEG :
                return new AcImageJPG($filePath);
            case self::PNG :
                return new AcImagePNG($filePath);
            case self::GIF :
                return new AcImageGIF($filePath);
            default:
                throw new InvalidFileException($filePath);
        }
    }

    /**
     * @param $path
     * @return $this
     * @throws FileAlreadyExistsException
     * @throws FileNotSaveException
     * @throws UnsupportedFormatException
     */
    public function saveAsJPG($path)
    {
        if (!AcImageJPG::isSupport())
            throw new UnsupportedFormatException();

        if (!self::getRewrite() && self::isFileExists($path))
            throw new FileAlreadyExistsException($path);

        $this->putBackground(self::$backgroundColor);
        if (!imagejpeg(self::getResource(), $path, self::getQuality()))
            throw new FileNotSaveException($path);

        return $this;
    }

    /**
     * @param $path
     * @return $this
     * @throws FileAlreadyExistsException
     * @throws FileNotSaveException
     * @throws UnsupportedFormatException
     */
    public function saveAsPNG($path)
    {
        if (!AcImagePNG::isSupport())
            throw new UnsupportedFormatException('png');

        if (!self::getRewrite() && self::isFileExists($path))
            throw new FileAlreadyExistsException($path);

        if (!self::getTransparency())
            $this->putBackground(self::$backgroundColor);
        // php >= 5.1.2
        if (!imagePng(self::getResource(), $path, AcImagePNG::getQuality()))
            throw new FileNotSaveException($path);

        return $this;
    }

    /**
     * @param $path
     * @return $this
     * @throws FileAlreadyExistsException
     * @throws FileNotSaveException
     * @throws UnsupportedFormatException
     */
    public function saveAsGIF($path)
    {
        if (!AcImageGIF::isSupportedGD())
            throw new UnsupportedFormatException();

        if (!self::getRewrite() && self::isFileExists($path))
            throw new FileAlreadyExistsException($path);;

        if (!self::getTransparency())
            $this->putBackground(self::$backgroundColor);

        if (!imagegif(self::getResource(), $path))
            throw new FileNotSaveException($path);

        return $this;
    }

    /**
     * @return $this
     * @throws IllegalArgumentException
     */
    public function resize()
    {
        $args = func_get_args();
        if (count($args) == 2)
        {
            return $this->resize(new Size($args[0], $args[1]));
        } else if (count($args) == 1 && $args[0] instanceof Size) {
            $size = $args[0];
            $imageSize = $this->getSize()->getByFrame($size);

            $newImageResource = imagecreatetruecolor($imageSize->getWidth(), $imageSize->getHeight());
            imageAlphaBlending($newImageResource, false);
            imageSaveAlpha($newImageResource, true);
            imagecopyresampled($newImageResource, $this->getResource(), 0, 0, 0, 0, $imageSize->getWidth(),
                $imageSize->getHeight(), $this->getWidth(), $this->getHeight());
            $this->setResource($newImageResource);
            $this->setSize($imageSize);
            return $this;
        }
        throw new IllegalArgumentException();
    }

    /**
     * @param $width
     * @return $this
     * @throws IllegalArgumentException
     */
    public function resizeByWidth($width)
    {
        if (!is_int($width) || $width <= 0)
            throw new IllegalArgumentException();

        return $this->resize($width, $this->getHeight());
    }

    /**
     * @param $height
     * @return $this
     * @throws IllegalArgumentException
     */
    public function resizeByHeight($height)
    {
        if (!is_int($height) || $height <= 0)
            throw new IllegalArgumentException();

        return $this->resize($this->getWidth(), $height);
    }

    /**
     * @return $this
     * @throws IllegalArgumentException
     */
    public function crop()
    {
        $a = func_get_args();

        if (count($a) == 4)
            $rect = new Rectangle($a[0], $a[1], $a[2], $a[3]);
        else if (count($a) == 1 && $a[0] instanceof Rectangle)
            $rect = $a[0];
        $rect = $rect->getIntersectsWith(new Rectangle(new Point(0, 0), $this->getSize()));

        if (!$rect)
            throw new IllegalArgumentException();

        $width = $rect->getWidth();
        $height = $rect->getHeight();
        $x = $rect->getLeft();
        $y = $rect->getTop();

        if ($width == 0 || $height == 0)
            throw new IllegalArgumentException();

        $newImageResource = imagecreatetruecolor($width, $height);
        imageAlphaBlending($newImageResource, false);
        imageSaveAlpha($newImageResource, true);
        imagecopyresampled($newImageResource, $this->getResource(), 0, 0, $x, $y, $width, $height, $width, $height);
        $this->setResource($newImageResource);
        $this->setSize($rect->getSize());
        return $this;
    }

    /**
     * @return $this
     * @throws IllegalArgumentException
     */
    public function cropSquare()
    {
        $a = func_get_args();
        if (count($a) == 1 && $a[0] instanceof Rectangle && $a[0]->isSquare()) {
            $square = $a[0];
        } else if (count($a) == 2 && $a[0] instanceof Point && is_int($a[1])) {
            $square = new Rectangle($a[0], new Size($a[1], $a[1]));
        } else if (count($a) == 3) {
            $square = new Rectangle(new Point($a[0], $a[1]), new Size($a[2], $a[2]));
        } else {
            throw new IllegalArgumentException();
        }

        if (!$square->isInner(new Rectangle(new Point(0, 0), $this->getSize())))
            throw new IllegalArgumentException();

        return $this->crop($square);
    }

    /**
     * @param $width
     * @param $height
     * @return $this
     * @throws IllegalArgumentException
     */
    public function cropCenter($width, $height)
    {
        $result = self::parseCropCenterArg($width);
        $widthUnits = $result['units'];
        $width = $result['value'];

        $result = self::parseCropCenterArg($height);
        $heightUnits = $result['units'];
        $height = $result['value'];

        if ($widthUnits == self::PROPORTION xor $heightUnits == self::PROPORTION)
            throw new IllegalArgumentException();

        if ($widthUnits == self::PERCENT)
            $width = self::percentToPixels($width, $this->getWidth());

        if ($heightUnits == self::PERCENT)
            $height = self::percentToPixels($height, $this->getHeight());

        if ($widthUnits == self::PROPORTION) {
            $size = $this->getSizeByProportion($width, $height);

            $width = $size->getWidth();
            $height = $size->getHeight();
        }

        $width = (int)min($width, $this->getWidth());
        $height = (int)min($height, $this->getHeight());

        $imageRect = new Rectangle(0, 0, $this->getWidth(), $this->getHeight());
        $cropRect = new Rectangle(0, 0, $width, $height);
        $cropRect = $cropRect->center($imageRect);

        return $this->crop($cropRect);
    }

    private static function percentToPixels($value, $imageSide)
    {
        return (int)round(($imageSide / 100 * $value));
    }

    /**
     * @param $width
     * @param $height
     * @return Size
     * @throws IllegalArgumentException
     */
    private function getSizeByProportion($width, $height)
    {
        $imageWidth = $this->getWidth();
        $imageHeight = $this->getHeight();

        if ($height / $imageHeight > $width / $imageWidth) {
            return new Size((int)round($imageHeight / $height * $width), $imageHeight);
        } else {
            return new Size($imageWidth, (int)round($imageWidth / $width * $height));
        }
    }

    /**
     * @param $arg
     * @return array
     * @throws IllegalArgumentException
     */
    private static function parseCropCenterArg($arg)
    {
        $pattern = '/^(\d+(\.\d+)*)(px|\%|pr)$/'; // in constants?

        $matches = array();
        if (is_int($arg)) {
            $units = self::PIXELS;
            $value = $arg;
        } else if (preg_match($pattern, $arg, $matches)) {
            $units = $matches[3];
            $value = $matches[1];
        } else {
            throw new IllegalArgumentException();
        }
        return array(
            'units' => $units,
            'value' => $value
        );
    }

    /**
     * @param $width
     * @param $height
     * @param int $c
     * @return $this
     * @throws IllegalArgumentException
     */
    public function thumbnail($width, $height, $c = 2)
    {
        if ($c <= 1 || !is_finite($c) || $width <= 0 || $height <= 0)
            throw new IllegalArgumentException();

        $size = new Size($width, $height);

        if ($this->getSize()->lessThen($size))
            $size = $this->getSize();

        $imageSize = $this->getSize();

        $isRotate = false;
        if ($size->getWidth() / $imageSize->getWidth() <= $size->getHeight() / $imageSize->getHeight()) {
            $size->flip();
            $imageSize->flip();
            $isRotate = true;
        }

        $width = $size->getWidth();
        $height = $size->getHeight();

        $imageWidth = $imageSize->getWidth();
        $imageHeight = $imageSize->getHeight();

        $lim = (int)($c * $height);
        $newHeight = (int)($imageHeight * $width / $imageWidth);

        if ($imageWidth > $width) {
            if ($newHeight <= $lim) {
                $size = new Size($width, $newHeight);
            } else {
                if ($newHeight <= 2 * $lim) {
                    $size = new Size((int)($imageWidth * $lim / $imageHeight), $lim);
                } else {
                    $size = new Size((int)($width / 2), (int)($imageHeight * $width / $imageWidth));
                }
            }
        } else {
            if ($imageHeight <= $lim) {
                $size = $this->getSize();
            } else {
                if ($imageHeight <= 2 * $lim) {
                    if ($imageWidth * $lim / $imageHeight >= $width / 2) {
                        $size = new Size((int)($imageWidth * $lim / $imageHeight), $lim);
                    } else {
                        $size = new Size((int)($width / 2), (int)($imageHeight * $width / ($imageWidth * 2)));
                    }
                } else {
                    $size = new Size((int)($width / 2), (int)($imageHeight * $width / ($imageWidth * 2)));
                }
            }
        }
        if ($isRotate) {
            $size->flip();
            $imageSize->flip();
        }
        return $this->resize($size);
    }

    /**
     * @param $logo
     * @param null $corner
     * @return $this
     * @throws FileNotFoundException
     * @throws GDnotInstalledException
     * @throws IllegalArgumentException
     * @throws InvalidFileException
     * @throws UnsupportedFormatException
     */
    public function drawLogo($logo, $corner = null)
    {
        if (is_null($corner))
            $corner = self::$cornerLogo;

        if (!AcImage::isCorrectCorner($corner))
            throw new IllegalArgumentException();

        if (is_string($logo))
            $logo = AcImage::createImage($logo);

        if (!($logo instanceof AcImage))
            throw new IllegalArgumentException();

        $maxWidthLogo = (int)($this->getWidth() * self::$maxProportionLogo);
        $maxHeightLogo = (int)($this->getHeight() * self::$maxProportionLogo);

        $logo->resize($maxWidthLogo, $maxHeightLogo);

        if (!self::getTransparency())
            $logo->putBackground(self::$backgroundColor);

        imagealphablending($this->getResource(), true);
        $logoSize = $logo->getSize();

        $location = $this->getLogoPosition($corner, $logoSize->getWidth(), $logoSize->getHeight());
        imagecopy($this->getResource(), $logo->getResource(), $location->getX(), $location->getY(), 0, 0,
            $logoSize->getWidth(), $logoSize->getHeight());

        return $this;
    }

    private function getLogoPosition($corner, $width, $height)
    {
        $paddingX = $this->getWidth() * self::$paddingProportionLogo;
        $paddingY = $this->getHeight() * self::$paddingProportionLogo;


        if ($corner == self::BOTTOM_RIGHT || $corner == self::BOTTOM_LEFT)
            $y = $this->getHeight() - $paddingY - $height;
        else
            $y = $paddingY;

        if ($corner == self::BOTTOM_RIGHT || $corner == self::TOP_RIGHT)
            $x = $this->getWidth() - $paddingX - $width;
        else
            $x = $paddingX;

        return new Point((int)$x, (int)$y);
    }

    private static function isCorrectCorner($corner)
    {
        return in_array($corner, self::$correctCorners);
    }

    public static function isFileExists($filePath)
    {
        if (@file_exists($filePath))
            return true;

        if (!preg_match("|^http(s)?|", $filePath))
            return false;

        $headers = @get_headers($filePath);
        if (preg_match("|200|", $headers[0]))
            return true;

        return false;
    }

    public static function isFileImage($filePath)
    {
        if (!self::isFileExists($filePath))
            return false;

        $imageInfo = @getimagesize($filePath);
        return is_array($imageInfo);
    }

    protected function putBackground()
    {
        $newImageResource = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        imagefill($newImageResource, 0, 0, self::getBackgroundColor()->getCode());
        imagecopyresampled($newImageResource, $this->getResource(), 0, 0, 0, 0,
            $this->getWidth(), $this->getHeight(), $this->getWidth(), $this->getHeight());
        $this->setResource($newImageResource);
    }

    /**
     * @param $mode
     * @throws IllegalArgumentException
     */
    public static function setRewrite($mode)
    {
        if (!is_bool($mode))
            throw new IllegalArgumentException();

        self::$rewrite = $mode;
    }

    public static function getRewrite()
    {
        return self::$rewrite;
    }

    public function getImageInfo()
    {
        if (!isset($this->imageInfo))
            $this->imageInfo = @getimagesize($this->getFilePath());

        return $this->imageInfo;
    }

    public static function getShortPHPVersion()
    {
        $matches = array();
        preg_match("@^\d\.\d@", phpversion(), $matches);
        return $matches[0];
    }

    public static function isSupportedGD()
    {
        return function_exists('gd_info');
    }

    public static function getGDinfo()
    {
        if (!self::isSupportedGD())
            return false;

        if (!isset(self::$gdInfo))
            self::$gdInfo = gd_info();

        return self::$gdInfo;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    private function setSize(Size $s)
    {
        $this->size = $s;
    }

    public function getSourceImage()
    {
        return $this->sourceImage;
    }

    public function getMimeType()
    {
        $imageInfo = $this->getImageInfo();
        return $imageInfo['mime'];
    }

    public function getSize()
    {
        return clone $this->size;
    }

    public function getWidth()
    {
        return $this->getSize()->getWidth();
    }

    public function getHeight()
    {
        return $this->getSize()->getHeight();
    }

    public function getResource()
    {
        return $this->resource;
    }

    protected function setResource($resource)
    {
        return $this->resource = $resource;
    }

    /**
     * @param $q
     * @throws IllegalArgumentException
     */
    public static function setQuality($q)
    {
        $q = (int)$q;
        if (!is_integer($q) || $q <= 0 || $q > 100)
            throw new IllegalArgumentException();

        self::$quality = $q;
    }

    public static function getQuality()
    {
        return self::$quality;
    }

    /**
     * @param $mode
     * @throws IllegalArgumentException
     */
    public static function setTransparency($mode)
    {
        if (!is_bool($mode))
            throw new IllegalArgumentException();

        self::$transparency = $mode;
    }

    public static function getTransparency()
    {
        return self::$transparency;
    }

    /**
     * @throws IllegalArgumentException
     * @throws geometry\InvalidChannelException
     */
    public static function setBackgroundColor() // $color || $r, $r, $b || $code
    {
        $a = func_get_args();
        if (count($a) == 1) {
            if ($a[0] instanceof AcColor) {
                self::$backgroundColor = $a[0];
            } else {
                self::$backgroundColor = new AcColor($a[0]);
            }
        } else if (count($a) == 3) {
            self::$backgroundColor = new AcColor($a[0], $a[1], $a[2]);
        } else {
            throw new IllegalArgumentException();
        }
    }

    /**
     * @return AcColor|int
     * @throws IllegalArgumentException
     * @throws geometry\InvalidChannelException
     */
    public static function getBackgroundColor()
    {
        if (is_integer(self::$backgroundColor))
            self::$backgroundColor = new AcColor(self::$backgroundColor);

        return self::$backgroundColor;
    }

    public function getCornerLogo()
    {
        return self::$cornerLogo;
    }

    /**
     * @param $corner
     * @throws IllegalArgumentException
     */
    public function setCornerLogo($corner)
    {
        if (!self::isCorrectCorner($corner))
            throw new IllegalArgumentException();

        self::$cornerLogo = $corner;
    }

    /**
     * @param $maxPropotionsLogo
     * @throws IllegalArgumentException
     */
    public static function setMaxProportionLogo($maxPropotionsLogo)
    {
        if (!is_float($maxPropotionsLogo) || $maxPropotionsLogo > 1 ||
            $maxPropotionsLogo <= 0)
            throw new IllegalArgumentException();

        self::$maxProportionLogo = $maxPropotionsLogo;
    }

    public static function getMaxProportionLogo()
    {
        return self::$maxProportionLogo;
    }

    /**
     * @param $paddingProportionLogo
     * @throws IllegalArgumentException
     */
    public static function setPaddingProportionLogo($paddingProportionLogo)
    {
        if (!is_float($paddingProportionLogo) || $paddingProportionLogo > 1 ||
            $paddingProportionLogo <= 0)
            throw new IllegalArgumentException();

        self::$paddingProportionLogo = $paddingProportionLogo;
    }

    public static function getPaddingProportionLogo()
    {
        return self::$paddingProportionLogo;
    }
}