<?php
function markdownToHtmlFile($markdown, $filename) {
    // Divise les lignes de la table
    $lines = explode("\n", trim($markdown));

    // Retire les lignes vides
    $lines = array_filter($lines);

    // La première ligne correspond à l'en-tête
    $headerLine = array_shift($lines);

    // Supprime la ligne des séparateurs
    array_shift($lines);

    // Traite l'en-tête
    $headers = array_map('trim', explode('|', trim($headerLine, '|')));

    // Traite les lignes de données
    $rows = array_map(function ($line) {
        return array_map('trim', explode('|', trim($line, '|')));
    }, $lines);

    // Génère le tableau HTML
    $tableHtml = "<table border='1'>\n<thead>\n<tr>\n";
    foreach ($headers as $header) {
        $tableHtml .= "<th>" . htmlspecialchars($header) . "</th>\n";
    }
    $tableHtml .= "</tr>\n</thead>\n<tbody>\n";

    foreach ($rows as $row) {
        $tableHtml .= "<tr>\n";
        foreach ($row as $cell) {
            $tableHtml .= "<td>" . htmlspecialchars($cell) . "</td>\n";
        }
        $tableHtml .= "</tr>\n";
    }

    $tableHtml .= "</tbody>\n</table>";

    // Cadre HTML complet
    $htmlContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table from Markdown</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Markdown to HTML Table</h1>
    $tableHtml
</body>
</html>
HTML;

    // Enregistre le contenu dans un fichier HTML
    file_put_contents($filename, $htmlContent);
    echo "Fichier HTML généré avec succès : $filename\n";
}

// Exemple d'utilisation
$markdownText = <<<MD
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

$filename = "table.html";
markdownToHtmlFile($markdownText, $filename);
?>
