<?php
namespace App\Abstracts;

use App\Interfaces\EntityInterface;

abstract class AbstractEntity implements EntityInterface {
    protected int $id;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }
}
