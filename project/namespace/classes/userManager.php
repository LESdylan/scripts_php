<?php
namespace App\Models;

use App\Traits\LoggerTrait;

class UserManager {
    use LoggerTrait;

    private array $users = [];

    public function addUser(User $user): void {
        $this->users[$user->getId()] = $user;
        $this->log("User {$user->getName()} added to UserManager.");
    }

    public function getUserById(int $id): ?User {
        if (isset($this->users[$id])) {
            $this->log("User with ID {$id} retrieved.");
            return $this->users[$id];
        }

        $this->log("User with ID {$id} not found.");
        return null;
    }

    public static function displayUserInfo(User $user): string {
        return "User [ID: {$user->getId()}, Name: {$user->getName()}]";
    }
}
