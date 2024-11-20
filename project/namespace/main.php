<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '0'); // Ne pas afficher les erreurs à l'écran
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/errors.log'); // Chemin du fichier de log

// Validation du chemin d'accès au fichier de log
if (!is_writable(dirname(__DIR__ . '/errors.log'))) {
    die("Le répertoire pour le fichier de log n'est pas accessible en écriture.");
}

// Gestionnaires personnalisés
function customErrorHandler(int $errno, string $errstr, string $errfile, int $errline): bool {
    $logMessage = "[ERROR] [" . date('Y-m-d H:i:s') . "] Error $errno: $errstr in $errfile on line $errline" . PHP_EOL;
    error_log($logMessage, 3, __DIR__ . '/errors.log');
    return true;
}

function customExceptionHandler(Throwable $exception): void {
    $logMessage = "[EXCEPTION] [" . date('Y-m-d H:i:s') . "] Uncaught exception: " . $exception->getMessage() .
        " in " . $exception->getFile() . " on line " . $exception->getLine() . PHP_EOL;
    error_log($logMessage, 3, __DIR__ . '/errors.log');
    exit(1);
}

function shutdownHandler(): void {
    $error = error_get_last();
    if ($error !== null) {
        $logMessage = "[FATAL ERROR] [" . date('Y-m-d H:i:s') . "] " .
            $error['message'] . " in " . $error['file'] . " on line " . $error['line'] . PHP_EOL;
        error_log($logMessage, 3, __DIR__ . '/errors.log');
    }
}

register_shutdown_function("shutdownHandler");

// Définir les gestionnaires personnalisés
set_error_handler("customErrorHandler");
set_exception_handler("customExceptionHandler");

// Import des classes
require_once 'classes/Interfaces/EntityInterface.php';
require_once 'classes/Abstracts/AbstractEntity.php';
require_once 'classes/Traits/LoggerTrait.php';
require_once 'classes/User.php';
require_once 'classes/Product.php';
require_once 'classes/UserManager.php';

use App\Models\User;
use App\Models\Product;
use App\Models\UserManager;

// Simulation
try {
    // Création d'utilisateurs
    $user1 = new User("Alice", 1);
    $user2 = new User("Bob", 2);

    // Gestion des utilisateurs
    $userManager = new UserManager();
    $userManager->addUser($user1);
    $userManager->addUser($user2);

    // Création de produits
    $product1 = new Product("Laptop", 1200.99, 101);
    $product2 = new Product("Phone", 799.49, 102);

    // Utilisation des méthodes statiques
    $discountedPrice = Product::calculateDiscount($product1->getPrice(), 10);

    // Affichage
    echo UserManager::displayUserInfo($user1) . PHP_EOL;
    echo "Product [ID: {$product1->getId()}, Title: {$product1->getTitle()}, Price: {$product1->getPrice()}, Discounted Price: {$discountedPrice}]" . PHP_EOL;

} catch (Throwable $e) {
    customExceptionHandler($e);
}
