<?php

namespace app\lib\img\geometry;


class Size
{
    private $width;
    private $height;

    /**
     * Size constructor.
     * @param $width
     * @param $height
     * @throws IllegalArgumentException
     */
    public function __construct($width, $height)
    {
        $this->setWidth($width);
        $this->setHeight($height);
    }

    public function greatThen(Size $s)
    {
        return $this->getWidth() > $s->getWidth() || $this->getHeight() > $s->getHeight();
    }

    public function lessThen(Size $s)
    {
        return !$this->greatThen($s) && !$this->equals($s);
    }

    public function isInner(Size $s)
    {
        return $this->getWidth() <= $s->getWidth() && $this->getHeight() <= $s->getHeight();
    }

    public function equals(Size $s)
    {
        return $this->getWidth() == $s->getWidth() && $this->getHeight() == $s->getHeight();
    }

    /**
     * @return $this
     * @throws IllegalArgumentException
     */
    public function flip()
    {
        $t = $this->getWidth();
        $this->setWidth($this->getHeight());
        $this->setHeight($t);
        return $this;
    }

    /**
     * @param $width
     * @return $this|Size
     * @throws IllegalArgumentException
     */
    public function getByWidth($width)
    {
        if (!is_integer($width)) {
            throw new IllegalArgumentException();
        }

        if ($width >= $this->getWidth()) {
            return $this;
        }

        $height = (int)round($this->getHeight() * $width / $this->getWidth());
        return new Size($width, $height);
    }

    /**
     * @param $height
     * @return $this|Size
     * @throws IllegalArgumentException
     */
    public function getByHeight($height)
    {
        if (!is_integer($height)) {
            throw new IllegalArgumentException();
        }

        if ($height >= $this->getHeight()) {
            return $this;
        }

        $width = (int)round($this->getWidth() * $height / $this->getHeight());
        return new Size($width, $height);
    }

    /**
     * @return $this|Size
     * @throws IllegalArgumentException
     */
    public function getByFrame()
    {
        $args = func_get_args();
        if (count($args) == 2) {
            $this->getByFrame(new Size($args[0], $args[1]));
        } else if (count($args) == 1 && $args[0] instanceof Size) {
            $frame = $args[0];
        } else {
            throw new IllegalArgumentException();
        }

        if ($frame->getWidth() <= 0 || $frame->getHeight() <= 0)
            throw new IllegalArgumentException();

        if ($this->isInner($frame))
            return $this;

        $height = $frame->getHeight();
        $width = $frame->getWidth();

        if ($this->getWidth() / $width > $this->getHeight() / $height)
            return $this->getByWidth($width);

        return $this->getByHeight($height);
    }

    /**
     * @param Size $s
     * @param $obj
     * @return Size
     * @throws IllegalArgumentException
     */
    public static function add(Size $s, $obj)
    {
        if ($obj instanceof Size) {
            return new Size($s->getWidth() + $obj->getWidth(), $s->getHeight() + $obj->getHeight());
        } else if ($obj instanceof Point) {
            return new Size($s->getWidth() + $obj->getX(), $s->getHeigth() + $obj->getY());
        }
        throw new IllegalArgumentException();
    }

    /**
     * @param Size $s
     * @param $obj
     * @return Size
     * @throws IllegalArgumentException
     */
    public static function subtract(Size $s, $obj)
    {
        if ($obj instanceof Size) {
            return new Size($s->getWidth() - $obj->getWidth(), $s->getHeight() - $obj->getHeight());
        } else if ($obj instanceof Point) {
            return new Size($s->getWidth() - $obj->getX(), $s->getHeigth() - $obj->getY());
        }
        throw new IllegalArgumentException();
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param $width
     * @throws IllegalArgumentException
     */
    public function setWidth($width)
    {
        if (is_integer($width)) {
            $this->width = $width;
        } else {
            throw new IllegalArgumentException();
        }
    }

    /**
     * @param $height
     * @throws IllegalArgumentException
     */
    public function setHeight($height)
    {
        if (is_integer($height)) {
            $this->height = $height;
        } else {
            throw new IllegalArgumentException();
        }
    }

    public function __toString()
    {
        return "{width: {$this->width}, height: {$this->height}}";
    }
}
