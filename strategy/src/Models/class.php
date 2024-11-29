<?php
declare(strict_types=1);

namespace App\Src;

use Exception;
use InvalidArgumentException;

/**
 * Classe MarkdownConverter
 * Convertit du texte Markdown en HTML avec prise en charge des listes imbriquées,
 * tableaux, et autres formats Markdown courants.
 */
class MarkdownConverter
{
    private ?string $markdownFile;
    private ?string $markdownText;

    // Constructor accepts a file path
    public function __construct(string $markdownFile)
    {
        $this->markdownFile = $markdownFile;
        $this->loadFromFile();
    }

    // Convert headings
    private function cvtHeadings(string $mdText): string
    {
        $patterns = [
            '/^#{1} (.*)$/m',
            '/^#{2} (.*)$/m',
            '/^#{3} (.*)$/m',
            '/^#{4} (.*)$/m',
            '/^#{5} (.*)$/m',
            '/^#{6} (.*)$/m',
        ];
        $replacements = [
            '<h1 class="title">$1</h1>',
            '<h2 class="title">$1</h2>',
            '<h3 class="title">$1</h3>',
            '<h4 class="title">$1</h4>',
            '<h5 class="title">$1</h5>',
            '<h6 class="title">$1</h6>',
        ];
        return preg_replace($patterns, $replacements, $mdText);
    }
    // Ajouter cette méthode à la classe MarkdownConverter
    public function getMarkdownText(): ?string
    {
        return $this->markdownText;
    }
    
    // Convert links
    private function cvtLinks(string $mdText): string
    {
        return preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>', $mdText);
    }

    // Convert blockquote
    private function cvtBlockquote(string $mdText): string
    {
        return preg_replace('/^> (.*)$/m', '<blockquote>$1</blockquote>', $mdText);
    }

    // Convert horizontal rules
    private function cvtSeparators(string $mdText): string
    {
        return preg_replace('/(?<=\n|^)(-{3,})(?=\n|$)/m', '<hr>', $mdText);
    }

    // Convert code blocks
    private function cvtBlockCode(string $mdText): string
    {
        return preg_replace('/```(.*?)```/s', '<pre><code>$1</code></pre>', $mdText);
    }

    // Convert inline code
    private function cvtLineCode(string $mdText): string
    {
        return preg_replace('/`([^`]+)`/', '<code>$1</code>', $mdText);
    }

    // Convert bold, italic, strikethrough text
    private function cvtBoldStrikeItalic(string $mdText): string
    {
        $patterns = [
            '/\*\*(.*?)\*\*/',
            '/__(.*?)__/',
            '/\*(.*?)\*/',
            '/_(.*?)_/',
            '/~~(.*?)~~/',
        ];
        $replacements = [
            '<strong>$1</strong>',
            '<strong>$1</strong>',
            '<em>$1</em>',
            '<em>$1</em>',
            '<del>$1</del>',
        ];
        return preg_replace($patterns, $replacements, $mdText);
    }

    // Convert bulleted and numbered lists with nesting support
    private function cvtLists(string $mdText): string
    {
        $lines = explode("\n", $mdText);
        $html = '';
        $listStack = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^(\s*)(\-|\*)\s+(.*)$/', $line, $matches)) {
                $html .= $this->handleIndentedList($listStack, 'ul', strlen($matches[1]) / 2, $matches[3]);
            } elseif (preg_match('/^(\s*)(\d+)\.\s+(.*)$/', $line, $matches)) {
                $html .= $this->handleIndentedList($listStack, 'ol', strlen($matches[1]) / 2, $matches[3]);
            } else {
                $html .= "<p>" . htmlspecialchars($line) . "</p>\n";
            }
        }

        // Fermer toutes les balises de liste ouvertes
        while (!empty($listStack)) {
            $html .= "</" . array_pop($listStack)['type'] . ">\n";
        }

        return $html;
    }

    public function handleIndentedList(&$listStack, $listType, $indentLevel, $content): string
    {
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

    // Convert Markdown table to HTML
    private function cvtTable(string $line, &$inTable, &$currentTableHeaders): string
    {
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

    // Convert Markdown to HTML
    public function convert(string $mdText): string
    {
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

        $html = '';
        $lines = explode("\n", $mdText);
        $listStack = [];
        $inTable = false;
        $currentTableHeaders = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^(#+)\s+(.*)$/', $line, $matches)) {
                // Gestion des titres
                $html .= $this->cvtHeadings($matches[0]);
            } elseif (preg_match('/^\|(.+)\|$/', $line)) {
                // Gestion des tableaux
                $html .= $this->cvtTable($line, $inTable, $currentTableHeaders);
            } else {
                // Gestion du texte normal
                $html .= "<p>" . htmlspecialchars($line) . "</p>\n";
            }
        }

        // Fermer toutes les balises ouvertes
        $html .= closeAllOpenTags($listStack, $inTable);

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

    // Save HTML to file
    public function saveToFile(string $outputFile): void
    {
        $htmlContent = $this->convert($this->markdownText);
        file_put_contents($outputFile, $htmlContent);
    }
}
