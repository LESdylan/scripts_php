<?php
// Fonction pour loguer les erreurs avec horodatage dans le fichier de log
function logErrorHTML($message)
{
    // Utilisation de __DIR__ pour s'assurer que le chemin est correct
    $logFile = __DIR__ . '/../var/error_log.html';

    // Ajouter l'horodatage et la couleur du message
    $timestamp = date('Y-m-d H:i:s');
    $formattedMessage = "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>";
    $formattedMessage .= "<strong style='color: #FF0000;'>ERROR</strong>";
    $formattedMessage .= "<p><strong>Time:</strong> $timestamp</p>";
    $formattedMessage .= "<p><strong>Message:</strong> $message</p>";
    $formattedMessage .= "</div>";

    // Vérifier que le dossier existe, sinon le créer
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    // Enregistrer l'erreur dans le fichier de log HTML
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

// Gestion des erreurs
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $errorMessage = "Error [$errno]: $errstr in $errfile on line $errline";
    logErrorHTML($errorMessage); // Loguer dans le fichier HTML
    // Affichage des erreurs dans le terminal
    if ($errno == E_USER_ERROR) {
        echo "Erreur critique : $errstr\n";
    } elseif ($errno == E_WARNING) {
        echo "Avertissement : $errstr\n";
    } else {
        echo "Information : $errstr\n";
    }
});

// Gestion des exceptions
set_exception_handler(function ($exception) {
    $errorMessage = "Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
    logErrorHTML($errorMessage); // Loguer dans le fichier HTML
    echo "Exception : " . $exception->getMessage() . "\n";
});

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure la logique des fichiers spécifiques
require_once __DIR__ . '/Models/class.php';    // Inclure class.php
require_once __DIR__ . '/Utils/md_to_html.php'; // Inclure md_to_html.php

use App\Src\MarkdownConverter;
use function App\Src\validityArray;

// Partie 1 : Tester la fonction validityArray
$abstracts = [
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

// Ici, nous créons un fichier temporaire pour le Markdown
$tempMarkdownFile = __DIR__ . '/../var/temp_markdown.md';
$file = <<<MD
# Titre de niveau 1

## Titre de niveau 2 avec **gras** et _italique_

### Titre de niveau 3 avec ~~texte barré~~ et `code en ligne`

#### Titre de niveau 4 avec lien [vers Google](https://www.google.com)

---

### Texte de base

Voici un paragraphe simple.  
Voici un autre paragraphe avec **gras**, *italique*, et **_gras et italique_**.  
~~Ceci est un texte barré~~.

Voici un texte avec `code en ligne`.

---

### Listes

#### Liste non ordonnée
- Élément 1
  - Sous-élément 1.1
    - Sous-sous-élément 1.1.1
      - Sous-sous-sous-élément 1.1.1.1
  - Sous-élément 1.2
- Élément 2
- Élément 3

#### Liste ordonnée
1. Élément 1
   1. Sous-élément 1.1
      1. Sous-sous-élément 1.1.1
   2. Sous-élément 1.2
2. Élément 2
3. Élément 3

#### Liste mixte
1. Élément 1
   - Sous-élément 1.1 (liste non ordonnée)
     - Sous-sous-élément 1.1.1
   1. Sous-élément 1.2 (liste ordonnée)
      - Sous-sous-élément 1.2.1
2. Élément 2

---

### Tableaux

#### Table simple
| En-tête 1 | En-tête 2 | En-tête 3 |
|-----------|-----------|-----------|
| Cellule 1 | Cellule 2 | Cellule 3 |
| Cellule 4 | Cellule 5 | Cellule 6 |

#### Table complexe
| Produit     | Prix       | Disponibilité |
|-------------|------------|---------------|
| Pomme       | 0,50 €     | En stock      |
| Orange      | 0,80 €     | En stock      |
| Mangue      | 1,50 €     | **Rupture**   |
| Banane      | ~~1,00 €~~ | Promo : 0,75 €|

---

### Citations

> Ceci est une citation simple.

> Ceci est une citation imbriquée.  
>> Sous-citation.  
>>> Sous-sous-citation.

> Citation avec **formatage** et [un lien](https://www.example.com).

---

### Code

#### Code en ligne
Voici un exemple de `code en ligne`.

#### Bloc de code (général)
MD;

file_put_contents($tempMarkdownFile, $file);
echo "\n== Test de la classe MarkdownConverter ==\n";

try {
    // Passer le fichier Markdown à la classe MarkdownConverter
    $converter = new MarkdownConverter($tempMarkdownFile);

    // Charger le texte Markdown
    $markdownText = $converter->getMarkdownText();

    // Convertir en HTML
    $htmlOutput = $converter->convert($markdownText);

    // Sauvegarder le HTML généré dans un fichier
    $outputFile = __DIR__ . '/../var/output.html';
    
    // Créer le dossier de sortie si nécessaire
    $outputDir = dirname($outputFile);
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    
    $html = <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        $htmlOutput
    </body>
    </html>
    HTML;
    $contentHtml = file_put_contents($outputFile, $html); 
    echo "HTML généré enregistré dans 'var/output.html'. Ouvrez-le dans un navigateur pour voir le résultat.\n";
} catch (Exception $e) {
    // Logguer l'exception
    logErrorHTML("Erreur dans la conversion Markdown : " . $e->getMessage());
    echo "Une erreur est survenue lors de la conversion du Markdown. Veuillez vérifier le fichier de log pour plus de détails.\n";
}