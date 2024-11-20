<?php
namespace App\Models;

use App\Abstracts\AbstractEntity;
use App\Traits\LoggerTrait;

class User extends AbstractEntity {
    use LoggerTrait;

    private string $name;

    public function __construct(string $name, int $id) {
        $this->name = $name;
        $this->setId($id);
        $this->log("User {$this->name} created with ID {$this->getId()}");
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->log("User name changed from {$this->name} to {$name}");
        $this->name = $name;
    }
}
