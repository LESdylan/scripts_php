<?php
namespace App\Models;

use App\Abstracts\AbstractEntity;
use App\Traits\LoggerTrait;

class Product extends AbstractEntity {
    use LoggerTrait;

    private string $title;
    private float $price;

    public function __construct(string $title, float $price, int $id) {
        $this->title = $title;
        $this->price = $price;
        $this->setId($id);
        $this->log("Product {$this->title} created with ID {$this->getId()} and price {$this->price}");
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->log("Product title changed from {$this->title} to {$title}");
        $this->title = $title;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price): void {
        $this->log("Product price changed from {$this->price} to {$price}");
        $this->price = $price;
    }

    public static function calculateDiscount(float $price, float $discount): float {
        return $price - ($price * $discount / 100);
    }
}
