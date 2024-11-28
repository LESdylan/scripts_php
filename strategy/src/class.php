<?php
declare(strict_types=1);

namespace App\Src;

use Exception;

/**
 * !vulnerability XSS with `<`; `>`; `&`
 * *escape all the content users with htmlspecialchars before applying conversion. apply this only with raw text, not within html tags
 * ! Currently error on logi tables that verify if the footer always exists which is not always the case
 * * we have to verify if it exists
 * !Currently the nestes lists are not take in chare which can cause an incorrect HTML.
 * * Use recursive managemnent or using a parsing markdown more advanced to detect nested list.
 * !
 */
class MarkdownConverter
{
    private ?string $markdownFile;
    private ?string $markdownText;

    // Constructor accepts either text or a file path
    public function __construct(?string $markdownText = null, ?string $markdownFile = null)
    {
        $this->markdownFile = $markdownFile;
        $this->markdownText = $markdownText;
    }

    // Convert headings
    private function cvtHeadings(string $mdText): string
    {
        $mdText = preg_replace('/^#{1} (.*)$/m', '<h1 class="title">$1</h1>', $mdText);
        $mdText = preg_replace('/^#{2} (.*)$/m', '<h2 class="title">$1</h2>', $mdText);
        $mdText = preg_replace('/^#{3} (.*)$/m', '<h3 class="title">$1</h3>', $mdText);
        $mdText = preg_replace('/^#{4} (.*)$/m', '<h4 class="title">$1</h4>', $mdText);
        $mdText = preg_replace('/^#{5} (.*)$/m', '<h5 class="title">$1</h5>', $mdText);
        $mdText = preg_replace('/^#{6} (.*)$/m', '<h6 class="title">$1</h6>', $mdText);
        return $mdText;
    }

    // Convert links //! duplicate brackets
    private function cvtLinks(string $mdText): string
    {
        return preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $mdText);
    }

    private function cvtBulletedList(string $mdText): string
    {
        $lines = explode("\n", trim($mdText));
        $html = '';
        $stack = []; // Pile pour suivre les listes ouvertes
        $prevIndent = 0;
        $indentLevel = 0; // Niveau d'indentation pour la sortie HTML
    
        foreach ($lines as $line) {
            // Ignorer les lignes vides ou nulles
            if ($line === null || trim($line) === '') {
                continue; // Passer cette ligne
            }
    
            // Détecter l'indentation (nombre d'espaces au début de la ligne)
            $currentIndent = strlen($line) - strlen(ltrim($line));
            $line = trim($line);
    
            // Déterminer le type de liste (ordonnée ou non ordonnée)
            if (preg_match('/^\d+\.\s+/', $line)) {
                $type = 'ol'; // Liste ordonnée
            } elseif (preg_match('/^[-*]\s+/', $line)) {
                $type = 'ul'; // Liste non ordonnée
            } else {
                continue; // Ignorer les lignes non valides
            }
    
            // Nettoyer la ligne pour récupérer le contenu
            $lineContent = preg_replace('/^(\d+\.\s+|[-*]\s+)/', '', $line);
    
            // Gestion des niveaux d'indentation
            if ($currentIndent > $prevIndent) {
                // Une sous-liste commence
                $html .= str_repeat('  ', $indentLevel) . "<$type>\n";
                array_push($stack, $type);
                $indentLevel++; // Augmenter l'indentation pour les sous-listes
            } elseif ($currentIndent < $prevIndent) {
                // Fermer les listes jusqu'à atteindre le bon niveau
                while ($currentIndent < $prevIndent && !empty($stack)) {
                    $indentLevel--; // Réduire l'indentation
                    $html .= str_repeat('  ', $indentLevel) . '</' . array_pop($stack) . ">\n";
                    $prevIndent -= 2; // Réduction dynamique pour les sous-niveaux
                }
            }
    
            // Si le type de liste change au même niveau
            if (!empty($stack) && end($stack) !== $type) {
                $html .= str_repeat('  ', $indentLevel) . '</' . array_pop($stack) . ">\n";
                $html .= str_repeat('  ', $indentLevel) . "<$type>\n";
                array_push($stack, $type);
            }
    
            // Ajouter l'élément de liste avec indentation
            $html .= str_repeat('  ', $indentLevel) . "<li>$lineContent</li>\n";
            $prevIndent = $currentIndent;
        }
    
        // Fermer toutes les listes restantes
        while (!empty($stack)) {
            $indentLevel--; // Réduire l'indentation
            $html .= str_repeat('  ', $indentLevel) . '</' . array_pop($stack) . ">\n";
        }
    
        return $html;
    }
    


    // Convert numbered list
    //private function cvtNumberedList(string $mdText): string
    //{
    //    // Transformer chaque ligne numérotée en <li> sans échappement HTML
    //    $mdText = preg_replace_callback('/^(\d+\.)\s*(.*)$/m', function ($matches) {
    //        // Garder le numéro dans un autre endroit pour être sûr qu'il ne soit pas dans <li>
    //        return '<li>' . trim($matches[2]) . '</li>';
    //    }, $mdText);
    //
    //        return '<ol>'.PHP_EOL.  $mdText . PHP_EOL.'</ol>'. PHP_EOL;
//
    //
    //    return $mdText;
    //}
    
    
    



    // Convert blockquote
    private function cvtBlockquote(string $mdText): string
    {
        return preg_replace('/^> (.*)$/m', '<blockquote>$1</blockquote>', $mdText);
    }

    //*[x] Convert separators (---)
    private function cvtSeparators(string $mdText): string
    {
        return preg_replace('/(?<=\n|^)(-{3,})(?=\n|$)/m', '<hr>', $mdText);
    }

    // Convert block code
    private function cvtBlockCode(string $mdText): string
    {
        return preg_replace('/`{3}(.*)`{3}/sU', '<pre><code>$1</code></pre>', $mdText);
    }

    // Convert inline code
    private function cvtLineCode(string $mdText): string
    {
        return preg_replace('/`([^`]+)`/', '<code>$1</code>', $mdText);
    }

    // Convert images
    //private function cvtImages(string $mdText): string
    //{
    //    return preg_replace('/!\[([^\]]+)\]\(([^)]+)\)/', '<img src="$2" alt="$1">', $mdText);
    //}
//
    //// Convert tables
    //private function cvtTables(string $mdText): string
    //{
    //    $lines = explode("\n", trim($mdText));
    //    $lines = array_filter($lines);
//
    //    // La première ligne correspond à l'en-tête
    //    $headerLine = array_shift($lines);
    //
    //    // Supprime la ligne des séparateurs
    //    array_shift($lines);
    //
    //    // Traite l'en-tête
    //    $headers = array_map('trim', explode('|', trim($headerLine, '|')));
    //
    //    // Traite les lignes de données
    //    $rows = array_map(function ($line) {
    //        return array_map('trim', explode('|', trim($line, '|')));
    //    }, $lines);
    //
    //    // Génère le tableau HTML
    //    $tableHtml = "<table border='1'>\n<thead>\n<tr>\n";
    //    foreach ($headers as $header) {
    //        $tableHtml .= "<th>" . htmlspecialchars($header) . "</th>\n";
    //    }
    //    $tableHtml .= "</tr>\n</thead>\n<tbody>\n";
    //
    //    foreach ($rows as $row) {
    //        $tableHtml .= "<tr>\n";
    //        foreach ($row as $cell) {
    //            $tableHtml .= "<td>" . htmlspecialchars($cell) . "</td>\n";
    //        }
    //        $tableHtml .= "</tr>\n";
    //    }
    //
    //    $tableHtml .= "</tbody>\n</table>";
    //    return $tableHtml;
    //}
    

    // Convert bold, italic, strikethrough text
    private function cvtBoldStrikeItalic(string $mdText): string
    {
        $mdText = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $mdText);
        $mdText = preg_replace('/__(.*?)__/', '<strong>$1</strong>', $mdText);
        $mdText = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $mdText);
        $mdText = preg_replace('/_(.*?)_/', '<em>$1</em>', $mdText);
        $mdText = preg_replace('/~~(.*?)~~/', '<del>$1</del>', $mdText);
        return $mdText;
    }

    // Convert Markdown to HTML
    public function convert(): string
    {
        if ($this->markdownText === null) {
            throw new InvalidArgumentException('Markdown text cannot be null.');
        }
    
        $mdText = $this->markdownText;
        $mdText = $this->cvtHeadings($mdText);
        $mdText = $this->cvtBoldStrikeItalic($mdText);
        $mdText = $this->cvtLinks($mdText);
        $mdText = $this->cvtBulletedList($mdText);
        //$mdText = $this->cvtNumberedList($mdText);
        $mdText = $this->cvtBlockquote($mdText);
        $mdText = $this->cvtBlockCode($mdText);
        $mdText = $this->cvtLineCode($mdText);
        //$mdText = $this->cvtImages($mdText);
        //$mdText = $this->cvtTables($mdText);
        $mdText = $this->cvtSeparators($mdText);
        // Embed converted Markdown into an HTML document
        $html = <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Converted Markdown</title>
        <link rel="stylesheet" href="error_log.css">
    </head>
    <body>
        $mdText
    </body>
    </html>
    HTML;
    
        return $html;
    }

    // Load Markdown from file
    public function loadFromFile(): void
    {
        if ($this->markdownFile && file_exists($this->markdownFile)) {
            $this->markdownText = file_get_contents($this->markdownFile);
        } else {
            throw new Exception('Markdown file not found or invalid file path.');
        }
    }
}
