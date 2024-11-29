<?php
function markdownTableToHtml($markdown) {
    $lines = explode("\n", trim($markdown));
    $html = "<table>\n";

    foreach ($lines as $index => $line) {
        // Enlever les pipes aux extrémités et diviser les cellules
        $line = trim($line, '|');
        $cells = array_map('trim', explode('|', $line));

        if ($index == 0) {
            // Générer le header
            $html .= "<thead><tr>";
            foreach ($cells as $cell) {
                $html .= "<th>" . htmlspecialchars($cell) . "</th>";
            }
            $html .= "</tr></thead>\n<tbody>\n";
        } elseif ($index == count($lines) - 1 && strpos($line, '---') === false) {
            // Ajouter une section footer si la dernière ligne n'est pas un séparateur
            $html .= "</tbody>\n<tfoot><tr>";
            foreach ($cells as $cell) {
                $html .= "<td>" . htmlspecialchars($cell) . "</td>";
            }
            $html .= "</tr></tfoot>\n";
        } elseif ($index != 1) {
            // Ajouter les lignes du body (ignorer le séparateur)
            $html .= "<tr>";
            foreach ($cells as $cell) {
                $html .= "<td>" . htmlspecialchars($cell) . "</td>";
            }
            $html .= "</tr>\n";
        }
    }

    $html .= "</tbody>\n</table>";
    return $html;
}

// Exemple de tableau Markdown
$markdown = <<<MD
| Nom       | Âge | Ville       |
|-----------|-----|-------------|
| Alice     | 25  | Paris       |
| Bob       | 30  | Marseille   |
| Charlie   | 35  | Lyon        |
|-----------|-----|-------------|
| Footer    | -   | Fin         |
MD;

// Convertir en HTML
$tableHtml = markdownTableToHtml($markdown);

// Générer un fichier index.html
$htmlTemplate = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau Markdown</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        thead {
            background-color: #f4f4f4;
        }
        tfoot {
            background-color: #f4f4f4;
            font-style: italic;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Tableau Converti de Markdown</h1>
    {$tableHtml}
</body>
</html>
HTML;

// Écrire le fichier HTML
file_put_contents('index.html', $htmlTemplate);

echo "Le fichier index.html a été généré avec succès !";
?>
