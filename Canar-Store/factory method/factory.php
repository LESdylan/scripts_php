<?php

// Interface du produit
interface Product {
    public function getName(): string;
}

// Implémentation d’un produit concret
class ConcreteProduct implements Product {
    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }
}

// Interface de la Factory
interface ProductFactory {
    public function createProduct(string $name): Product;
}

// Implémentation de la Factory
class ConcreteProductFactory implements ProductFactory {
    public function createProduct(string $name): Product {
        return new ConcreteProduct($name);
    }
}

// Utilisation de la Factory
$factory = new ConcreteProductFactory();
$product = $factory->createProduct("Exemple");
echo $product->getName(); // Affiche "Exemple"