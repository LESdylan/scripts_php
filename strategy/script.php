<?php
// Fonction pour loguer les erreurs avec horodatage dans le fichier de log
function logErrorHTML($message)
{
    $logFile = __DIR__ . '/error_log.html';  // Chemin vers le fichier HTML

    // Ajouter l'horodatage et la couleur du message
    $timestamp = date('Y-m-d H:i:s');
    $formattedMessage = "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>";
    $formattedMessage .= "<strong style='color: #FF0000;'>ERROR</strong>";
    $formattedMessage .= "<p><strong>Time:</strong> $timestamp</p>";
    $formattedMessage .= "<p><strong>Message:</strong> $message</p>";
    $formattedMessage .= "</div>";

    // Enregistrer l'erreur dans le fichier de log HTML
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

// Gestion des erreurs
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $errorMessage = "Error [$errno]: $errstr in $errfile on line $errline";
    logErrorHTML($errorMessage);  // Loguer dans le fichier HTML
    // Affichage coloré des erreurs dans le terminal
    if ($errno == E_USER_ERROR) {
        echo "<strong style='color:red;'>Erreur critique :</strong> $errstr\n";
    } elseif ($errno == E_WARNING) {
        echo "<strong style='color:yellow;'>Avertissement :</strong> $errstr\n";
    } else {
        echo "<strong style='color:green;'>Information :</strong> $errstr\n";
    }
});

// Test avec une erreur manuelle
//trigger_error("Test d'erreur utilisateur", E_USER_ERROR);

// Gestion des exceptions
set_exception_handler(function ($exception) {
    $errorMessage = "Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
    logErrorHTML($errorMessage);  // Loguer dans le fichier HTML
    // Affichage coloré des exceptions dans le terminal
    echo "<strong style='color:blue;'>Exception :</strong> " . $exception->getMessage() . "\n";
});

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure la logique des fichiers spécifiques
require_once __DIR__ . '/src/class.php';    // Inclure class.php
require_once __DIR__ . '/src/md_to_html.php'; // Inclure md_to_html.php

use App\Src\MarkdownConverter;
use function App\Src\validityArray;

// Partie 1 : Tester la fonction validityArray
$abstracts = [  // Changement de $abstractdfd à $abstracts
    ['id' => 1, 'name' => 'Abstract 1', 'url' => 'http://example.com/1'],
    ['id' => 2, 'name' => '', 'url' => 'http://example.com/2'], // Invalid
    ['id' => 3, 'name' => 'Abstract 3', 'url' => 'not-a-url'], // Invalid
    ['id' => 4, 'name' => 'Abstract 4', 'url' => 'http://example.com/4'], // Valid
    ['id' => 5, 'name' => 'Abstract 5', 'url' => ''], // Invalid
];

echo "== Test de la fonction validityArray ==\n";
$validCount = validityArray($abstracts);
echo "Nombre total d'abstraits valides : $validCount\n";

// Partie 2 : Tester la classe MarkdownConverter
$markdownText = <<<MD
# Titre H1
## Titre H2
### Titre H3

Un paragraphe avec **du texte en gras**, *en italique*, ~~barré~~, et un [lien](http://example.com).

- Une liste
- Avec plusieurs
- Éléments

1. Liste numérotée
2. Deuxième élément

> Une citation

![Image](http://example.com/image.jpg)

---

Un bloc de code :
<?php echo "Hello, world!"; ?>
MD;

echo "\n== Test de la classe MarkdownConverter ==\n";

try {
    $converter = new MarkdownConverter($markdownText);
    $htmlOutput = $converter->convert();
    file_put_contents('output.html', $htmlOutput);
    echo "HTML généré enregistré dans 'output.html'. Ouvrez-le dans un navigateur pour voir le résultat.\n";
} catch (Exception $e) {
    // Logguer l'exception
    logErrorHTML("Erreur dans la conversion Markdown : " . $e->getMessage());
    echo "Une erreur est survenue lors de la conversion du Markdown. Veuillez vérifier le fichier de log pour plus de détails.\n";
}
?>
