<?php
declare(strict_types=1);

namespace App\Src;

use Exception;

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
    private function cvt_headings(string $mdText): string
    {
        $mdText = preg_replace('/^#{1} (.*)$/m', '<h1>$1</h1>', $mdText);
        $mdText = preg_replace('/^#{2} (.*)$/m', '<h2>$1</h2>', $mdText);
        $mdText = preg_replace('/^#{3} (.*)$/m', '<h3>$1</h3>', $mdText);
        $mdText = preg_replace('/^#{4} (.*)$/m', '<h4>$1</h4>', $mdText);
        $mdText = preg_replace('/^#{5} (.*)$/m', '<h5>$1</h5>', $mdText);
        $mdText = preg_replace('/^#{6} (.*)$/m', '<h6>$1</h6>', $mdText);
        return $mdText;
    }

    // Convert links
    private function cvt_links(string $mdText): string
    {
        return preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $mdText);
    }

    // Convert bulleted list
    private function cvt_bulletedList(string $mdText): string
    {
        $mdText = preg_replace('/^(\*|-) (.*)$/m', '<li>$2</li>', $mdText);
        return preg_replace('/(<li>.*?<\/li>)+/s', '<ul>$0</ul>', $mdText);
    }

    // Convert numbered list
    private function cvt_numberedList(string $mdText): string
    {
        $mdText = preg_replace('/^\d+\. (.*)$/m', '<li>$1</li>', $mdText);
        return preg_replace('/(<li>.*?<\/li>)+/s', '<ol>$0</ol>', $mdText);
    }

    // Convert blockquote
    private function cvt_blockquote(string $mdText): string
    {
        return preg_replace('/^> (.*)$/m', '<blockquote>$1</blockquote>', $mdText);
    }

    // Convert separators (---)
    private function cvt_separators(string $mdText): string
    {
        return preg_replace('/(?<=\n|^)(-{3,})(?=\n|$)/m', '<hr>', $mdText);
    }

    // Convert block code
    private function cvt_blockCode(string $mdText): string
    {
        return preg_replace('/```(.*?)```/s', '<pre><code>$1</code></pre>', $mdText);
    }

    // Convert inline code
    private function cvt_lineCode(string $mdText): string
    {
        return preg_replace('/`([^`]+)`/', '<code>$1</code>', $mdText);
    }

    // Convert images
    private function cvt_images(string $mdText): string
    {
        return preg_replace('/!\[([^\]]+)\]\(([^)]+)\)/', '<img src="$2" alt="$1">', $mdText);
    }

    // Convert tables
    private function cvt_tables(string $mdText): string
    {
        // Handle table headers and rows
        $mdText = preg_replace_callback(
            '/^\|(.+)\|\n\|(?:-+\|)+\n((?:\|.+\|\n)*)/m',
            function ($matches) {
                $header = '<tr><th>' . str_replace('|', '</th><th>', trim($matches[1])) . '</th></tr>';
                $rows = array_map(function ($row) {
                    return '<tr><td>' . str_replace('|', '</td><td>', trim($row)) . '</td></tr>';
                }, array_filter(explode("\n", trim($matches[2]))));
                return '<table>' . $header . implode('', $rows) . '</table>';
            },
            $mdText
        );
        return $mdText;
    }

    // Convert bold, italic, strikethrough text
    private function cvt_BoldStrikeItalic(string $mdText): string
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
            throw new Exception('Markdown text is empty.');
        }

        $mdText = $this->markdownText;
        $mdText = $this->cvt_headings($mdText);
        $mdText = $this->cvt_BoldStrikeItalic($mdText);
        $mdText = $this->cvt_links($mdText);
        $mdText = $this->cvt_bulletedList($mdText);
        $mdText = $this->cvt_numberedList($mdText);
        $mdText = $this->cvt_blockquote($mdText);
        $mdText = $this->cvt_blockCode($mdText);
        $mdText = $this->cvt_lineCode($mdText);
        $mdText = $this->cvt_images($mdText);
        $mdText = $this->cvt_tables($mdText);
        $mdText = $this->cvt_separators($mdText);

        return $mdText;
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
