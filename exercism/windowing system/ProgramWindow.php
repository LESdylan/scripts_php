<?php
include 'Position.php';
include 'Size.php';

class ProgramWindow 
{
    public $x;
    public $y;
    public $width;
    public $height;
    public Size $size;
    public Position $position;

    public function __construct()
    {
        $this->x = 0;
        $this->y = 0;
        $this->width = 800;
        $this->height = 600;
        $this->size = new Size($this->width, $this->height);
        $this->position = new Position($this->x, $this->y);
    }

    public function resize(Size $size): self
    {
        $this->size = $size;
        $this->width = $size->width;
        $this->height = $size->height;
        return $this;
    }

    public function move(Position $position): self
    {
        $this->position = $position;
        $this->x = $position->x;
        $this->y = $position->y;
        return $this;
    }

    public function getSize(): Size
    {
        return $this->size;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }
}

// Création d'une instance
$instance = new ProgramWindow();

// Redimensionnement
$size = new Size(100, 235);
$resize = $instance->resize($size);

// Déplacement
$position = new Position(40, 234);
$move= $instance->move($position);

// Affichage des nouvelles dimensions et positions
print_r([
    'Size' => $instance->getSize(),
    'Position' => $instance->getPosition(),
    'resize' => $resize,
    'move' => $move
]);
