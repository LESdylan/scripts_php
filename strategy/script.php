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
# Markdown Syntax Reference

This document covers all possible Markdown syntaxes with special characters used in creative ways.

---

## Headers
# Header 1 with \$pecial Characters
## Header 2 with @notation
### Header 3 with %utility
#### Header 4 with ^caret
##### Header 5 with &ampersand
###### Header 6 with ~tilde

---

## Emphasis
- *Italic* or _italic_ example with `*` and `_`
- **Bold** or __bold__ example
- ***Bold and Italic*** combined with a mix of `$` and `@`

---

## Lists

### Unordered List
- Item 1: Featuring a `%` sign
fdsfdsfdsfsdfdsffffffffffffffffffffffff
- Item 2: Contains a `$`
    - hello 
    sdfs
        - dfsfsdfsdfsdfsdfdsffd
- Item 3: Uses `@` and `~`

### Ordered List
1. First item
fsddfdsf
2. Second item includes `%var`
    - dfsfsdddddddddddddddddddddddddddddd 
3. Third item ends with `\$result`

---

## Links and Images

### Links
[Link with special @](https://example.com)  
Inline link `[example](https://example.com)` with no special characters.

### Images
![Image with ~caption](https://via.placeholder.com/150 "Placeholder")  
Alternative: `![Alt](url "title")`.

---

## Code and Syntax Highlighting

### Inline Code
Inline code uses ``backticks``:  
`const \$value = @input * %rate;`

### Block Code
```javascript
// JavaScript example using special characters
const \$value = @input * %rate;
console.log(\$value);
```
```html
dfsdfdsfds
```
````
```

| header1 | header2 | header3 |
|---------|---------|---------|
| val1    | val2    | val3    |
| val1    | val2    | val3    |
| val1    | val2    | val3    |
| val1    | val2    | val3    |
| val1    | val2    | val3    |
| val1    | val2    | val3    |
| val1    | val2    | val3    |
| val1    | val2    | val3    |
| val1    | val2    | val3    |
| val1    | val2    | val3    |

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
