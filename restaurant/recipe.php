<?php
namespace = recipe
class Recipe{
    // default constructor
    public function __construct()
    {

    }
    // creation method , operations of construction
    public static function __productAndCategory(string $product, string $category){
        $instance = new self();
        $instance->product = $product;
        $instance->category = $category;
        return $instance;
    }
    public function setRecipe()
    {

    }
    public function getRecipe()
    {

    }
}