<?php
class Position
{
    public $y;
    public $x;

    public function __construct(int $y, int $x)
    {
        $this->y = $y;
        $this->x= $x;
    }
}