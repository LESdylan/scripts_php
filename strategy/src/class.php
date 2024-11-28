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

    // Convert bulleted list
    private function cvtBulletedList(string $mdText): string
    {
        $mdText = preg_replace('/^(\*|-) (.*)$/m', '<li>$2</li>', $mdText);
        return preg_replace('/(<li>.*?<\/li>)+/s', '<ul>$0</ul>', $mdText);
    }

    // Convert numbered list
    private function cvtNumberedList(string $mdText): string
    {
        $mdText = preg_replace('/^\d+\. (.*)$/m', '<li>$1</li>', $mdText);
        return preg_replace('/(<li>.*?<\/li>)+/s', '<ol>$0</ol>', $mdText);
    }

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
    private function cvtImages(string $mdText): string
    {
        return preg_replace('/!\[([^\]]+)\]\(([^)]+)\)/', '<img src="$2" alt="$1">', $mdText);
    }

    // Convert tables
    private function cvtTables(string $mdText): string
    {
        // Handle table headers, rows, and optional footer
        $mdText = preg_replace_callback(
            '/^\|([^\n]+)\|\n\|([\-|\s]+)\|\n((?:\|[^\n]+\|\n)+)(?:\|([^\n]+)\|)?$/m',
            function ($matches) {
                // Header processing
                $header = '<thead><tr><th>' . implode(
                    '</th><th>',
                    array_map('htmlspecialchars', explode('|', trim($matches[1], '|')))
                ) . '</th></tr></thead>';
    
                // Body rows processing
                $rows = array_map(function ($row) {
                    return '<tr><td>' . implode(
                        '</td><td>',
                        array_map('htmlspecialchars', explode('|', trim($row, '|')))
                    ) . '</td></tr>';
                }, array_filter(explode("\n", trim($matches[3]))));
                $body = '<tbody>' . implode('', $rows) . '</tbody>';
    
                // Footer processing (if exists)
                $footer = '';
                if (!empty($matches[4])) {
                    $footer = '<tfoot><tr><td>' . implode(
                        '</td><td>',
                        array_map('htmlspecialchars', explode('|', trim($matches[4], '|')))
                    ) . '</td></tr></tfoot>';
                }
    
                // Combine table parts
                return '<table>' . $header . $body . $footer . '</table>';
            },
            $mdText
        );
        return $mdText;
    }
    

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
        $mdText = $this->cvtNumberedList($mdText);
        $mdText = $this->cvtBlockquote($mdText);
        $mdText = $this->cvtBlockCode($mdText);
        $mdText = $this->cvtLineCode($mdText);
        $mdText = $this->cvtImages($mdText);
        $mdText = $this->cvtTables($mdText);
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
