<?php

/**
 * Convertit un fichier Markdown en HTML.
 *
 * @param string $filePath Chemin du fichier Markdown.
 * @return string HTML généré.
 */
function markdownToHtml($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception("Le fichier spécifié n'existe pas : $filePath");
    }

    $markdownContent = file_get_contents($filePath);
    $html = '';
    $lines = explode("\n", $markdownContent);

    $listStack = []; // Pile pour gérer les listes (ul/ol) avec leur indentation.
    $inTable = false;
    $currentTableHeaders = [];

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if (preg_match('/^(#+)\s+(.*)$/', $trimmed, $matches)) {
            // Gestion des titres
            $html .= renderHeading($matches);
        } elseif (preg_match('/^(\s*)(\-|\*)\s+(.*)$/', $line, $matches)) {
            // Gestion des listes non ordonnées
            $html .= handleIndentedList($listStack, 'ul', strlen($matches[1]), $matches[3]);
        } elseif (preg_match('/^(\s*)(\d+)\.\s+(.*)$/', $line, $matches)) {
            // Gestion des listes ordonnées
            $html .= handleIndentedList($listStack, 'ol', strlen($matches[1]), $matches[3]);
        } elseif (preg_match('/^\|(.+)\|$/', $trimmed)) {
            // Gestion des tableaux
            $html .= handleTable($trimmed, $inTable, $currentTableHeaders);
        } elseif ($inTable) {
            // Fin de tableau
            $html .= "</table>\n";
            $inTable = false;
            $currentTableHeaders = [];
        } elseif (empty($trimmed)) {
            // Lignes vides : Fermer les balises ouvertes
            $html .= closeAllOpenTags($listStack, $inTable);
        } else {
            // Texte brut
            $html .= "<p>" . htmlspecialchars($trimmed) . "</p>\n";
        }
    }

    // Fermer toutes les balises ouvertes à la fin du fichier
    $html .= closeAllOpenTags($listStack, $inTable);

    return $html;
}

/**
 * Génère un titre HTML à partir d'un titre Markdown.
 */
function renderHeading($matches) {
    $level = strlen($matches[1]);
    $content = htmlspecialchars($matches[2]);
    return "<h$level>$content</h$level>\n";
}

/**
 * Gère les listes imbriquées (ordonnées ou non) avec indentation.
 */
function handleIndentedList(&$listStack, $listType, $indentLevel, $content) {
    $html = '';

    // Fermer les listes de niveau supérieur si nécessaire
    while (!empty($listStack) && end($listStack)['type'] !== $listType) {
        $html .= "</" . array_pop($listStack)['type'] . ">\n";
    }
    while (!empty($listStack) && end($listStack)['indent'] >= $indentLevel) {
        $html .= "</" . array_pop($listStack)['type'] . ">\n";
    }

    // Ouvrir une nouvelle liste si nécessaire
    if (empty($listStack) || end($listStack)['indent'] < $indentLevel) {
        $html .= "<$listType>\n";
        $listStack[] = ['type' => $listType, 'indent' => $indentLevel];
    }

    // Ajouter l'élément de liste
    $html .= "<li>" . htmlspecialchars($content) . "</li>\n";

    return $html;
}

/**
 * Gère les tableaux.
 */
function handleTable($line, &$inTable, &$currentTableHeaders) {
    $html = '';

    $cells = array_map('trim', explode('|', trim($line, '|')));

    if (!$inTable) {
        $html .= "<table>\n";
        $inTable = true;
    }

    // Vérifie si la ligne est une ligne de séparation de tableau (e.g., "---|---")
    if (preg_match('/^-+/', implode('', $cells))) {
        $currentTableHeaders = $cells; // Ligne d'en-tête détectée
    } elseif (!empty($currentTableHeaders)) {
        // Ajout d'une ligne d'en-têtes
        $html .= "<thead><tr>";
        foreach ($currentTableHeaders as $header) {
            $html .= "<th>" . htmlspecialchars($header) . "</th>";
        }
        $html .= "</tr></thead>\n";
        $html .= "<tbody>\n";
        $currentTableHeaders = []; // Réinitialiser après usage
    } else {
        // Ligne de contenu
        $html .= "<tr>";
        foreach ($cells as $cell) {
            $html .= "<td>" . htmlspecialchars($cell) . "</td>";
        }
        $html .= "</tr>\n";
    }

    return $html;
}

/**
 * Ferme toutes les balises ouvertes (listes ou tableau).
 */
function closeAllOpenTags(&$listStack, &$inTable) {
    $html = '';

    while (!empty($listStack)) {
        $html .= "</" . array_pop($listStack)['type'] . ">\n";
    }

    if ($inTable) {
        $html .= "</table>\n";
        $inTable = false;
    }

    return $html;
}

try {
    // Chemin du fichier Markdown
    $markdownFile = __DIR__ . '/document.md';

    // Chemin pour le fichier HTML de sortie
    $outputFile = __DIR__ . '/output.html';

    // Conversion du Markdown en HTML
    $htmlContent = markdownToHtml($markdownFile);

    // Génération de la structure HTML complète
    $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="markdownStyles.css"> <!-- Lien vers le CSS -->
</head>
<body>
$htmlContent
</body>
</html>
HTML;

    // Écriture du contenu HTML dans le fichier de sortie
    file_put_contents($outputFile, $html);

    // Message de succès
    echo "HTML généré enregistré dans 'output.html'. Ouvrez-le dans un navigateur pour voir le résultat.\n";
} catch (Exception $e) {
    // Gestion des erreurs
    echo "Erreur : " . $e->getMessage() . "\n";
}
