<?php

namespace app\lib\img\geometry;


class Rectangle
{
    private $location;
    private $size;

    /**
     * Rectangle constructor.
     * @throws IllegalArgumentException
     */
    public function __construct()
    {
        $args = func_get_args();
        if (count($args) == 4) {
            $this->setLocation($args[0], $args[1]);
            $this->setSize($args[2], $args[3]);
        } else if (count($args) == 2
            && $args[0] instanceof Point
            && $args[1] instanceof Size) {

            $this->setLocation($args[0]);
            $this->setSize($args[1]);
        }
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
     * @return $this
     * @throws IllegalArgumentException
     */
    public function inflate()
    {
        $args = func_get_args();

        if (count($args) == 2) {
            $this->setWidth($this->getWidth() + $args[0]);
            $this->setHeight($this->getHeight() + $args[1]);
        } else if (count($args) == 1) {
            $this->setSize(Size::add($this->getSize(), $args[0]));
        } else {
            throw new IllegalArgumentException();
        }

        return $this;
    }

    public function isIntersectsWithX(Rectangle $rect)
    {
        if ($rect->isNull() || $this->isNull()) {
            return false;
        }

        $left1 = $this->getLeft();
        $left2 = $rect->getLeft();

        if ($left1 < $left2) {
            return $left2 - $left1 < $this->getWidth();
        } else if ($left1 >= $left2) {
            return $left1 - $left2 < $rect->getWidth();
        }

        return false;
    }

    public function isIntersectsWithY(Rectangle $rect)
    {
        if ($rect->isNull() || $this->isNull()) {
            return false;
        }

        $top1 = $this->getTop();
        $top2 = $rect->getTop();

        if ($top1 < $top2) {
            return $top2 - $top1 < $this->getHeight();
        } else if ($top1 >= $top2) {
            return $top1 - $top2 < $rect->getHeight();
        }

        return false;
    }


    public function isIntersectsWith(Rectangle $rect)
    {
        return $this->isIntersectsWithX($rect) && $this->isIntersectsWithY($rect);
    }

    /**
     * @param Rectangle $rect
     * @return Rectangle|bool
     * @throws IllegalArgumentException
     */
    public function getIntersectsWith(Rectangle $rect)
    {
        if (!$this->isIntersectsWith($rect)) {
            return false;
        }
        $left = max($rect->getLeft(), $this->getLeft());
        $top = max($rect->getTop(), $this->getTop());

        $right = min($rect->getRight(), $this->getRight());
        $bottom = min($rect->getBottom(), $this->getBottom());

        $width = $right - $left;
        $height = $bottom - $top;

        return new Rectangle($left, $top, $width, $height);
    }

    public function isInner(Rectangle $rect)
    {
        return $this->getTop() >= 0 && $this->getLeft() >= 0 &&
            $this->getBottom() <= $rect->getHeight() && $this->getRight() <= $rect->getWidth();
    }

    public function isNull()
    {
        return $this->getWidth() == 0 && $this->getHeight() == 0;
    }

    /**
     * @return $this
     * @throws IllegalArgumentException
     */
    public function center()
    {
        $args = func_get_args();
        if (count($args) == 4) {
            return $this->center(new Rectangle($args[0], $args[1], $args[2], $args[3]));
        } else if (count($args) == 1 && $args[0] instanceof Rectangle) {
            $rect = $args[0];

            $left = (int)(($rect->getWidth() - $this->getWidth()) / 2) + $rect->getLeft();
            $top = (int)(($rect->getHeight() - $this->getHeight()) / 2) + $rect->getTop();

            $this->setLeft($left);
            $this->setTop($top);
            return $this;
        }
        throw new IllegalArgumentException();
    }

    /**
     * @return bool
     */
    public function isSquare()
    {
        return $this->getWidth() == $this->getHeight();
    }

    public function getLocation()
    {
        return clone $this->location;
    }

    public function getSize()
    {
        return clone $this->size;
    }

    public function getX()
    {
        return $this->location->getX();
    }

    public function getY()
    {
        return $this->location->getY();
    }

    public function getWidth()
    {
        return abs($this->getSize()->getWidth());
    }

    public function getHeight()
    {
        return abs($this->getSize()->getHeight());
    }

    public function getBottom()
    {
        if ($this->getSize()->getHeight() > 0) {
            return $this->getY() + $this->getHeight();
        } else {
            return $this->getY();
        }
    }

    public function getRight()
    {
        if ($this->getSize()->getWidth() < 0) {
            return $this->getX();
        } else {
            return $this->getX() + $this->getWidth();
        }
    }

    public function getTop()
    {
        if ($this->getSize()->getHeight() > 0) {
            return $this->getY();
        } else {
            return $this->getY() - $this->getHeight();
        }
    }

    public function getLeft()
    {
        if ($this->getSize()->getWidth() > 0) {
            return $this->getX();
        } else {
            return $this->getX() - $this->getWidth();
        }
    }

    /**
     * @return Point
     * @throws IllegalArgumentException
     */
    public function getTopLeft()
    {
        return new Point($this->getLeft(), $this->getTop());
    }

    /**
     * @throws IllegalArgumentException
     */
    public function setLocation()
    {
        $args = func_get_args();
        if (count($args) == 2) {
            $this->location = new Point($args[0], $args[1]);
        } else if (count($args) == 1 && $args[0] instanceof Point) {
            $this->location = $args[0];
        } else {
            throw new IllegalArgumentException();
        }
    }

    /**
     * @throws IllegalArgumentException
     */
    public function setSize()
    {
        $args = func_get_args();
        if (count($args) == 2) {
            $this->size = new Size($args[0], $args[1]);
        } else if (count($args) == 1 && $args[0] instanceof Size) {
            $this->size = $args[0];
        } else {
            throw new IllegalArgumentException();
        }
    }

    /**
     * @param $x
     */
    public function setX($x)
    {
        $this->location->setX($x);
    }

    /**
     * @param $y
     */
    public function setY($y)
    {
        $this->location->setY($y);
    }

    /**
     * @param $width
     */
    public function setWidth($width)
    {
        $this->size->setWidth($width);
    }

    /**
     * @param $height
     */
    public function setHeight($height)
    {
        $this->size->setHeight($height);
    }

    /**
     * @param $top
     * @throws IllegalArgumentException
     */
    public function setTop($top)
    {
        if (!is_int($top)) {
            throw new IllegalArgumentException();
        }

        if ($this->size->getHeight() > 0) {
            $this->setY($top);
        } else {
            $y = $top + $this->getHeight();
            $this->setY($y);
        }
    }

    /**
     * @param $left
     * @throws IllegalArgumentException
     */
    public function setLeft($left)
    {
        if (!is_int($left)) {
            throw new IllegalArgumentException();
        }

        if ($this->size->getWidth() > 0) {
            $this->setX($left);
        } else {
            $x = $left + $this->getWidth();
            $this->setX($x);
        }
    }

    public function __toString()
    {
        return "{location: {$this->location}, size: {$this->size}, left: {$this->getLeft()}, top: {$this->getTop()}}";
    }
}
