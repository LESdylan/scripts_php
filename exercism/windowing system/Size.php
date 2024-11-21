<?php

class Size
{
    public $height;
    public $width;

    public function __construct(int $height, int $width)
    {
        $this->height = $height;
        $this->width = $width;
    }
}
