<?php
require_once 'E:\script_php\strategy\vendor';
function main(): int
{
    $abstracts = [
        ['id' => 1, 'name' => 'git', 'url' => 'http://git.test/abstract'],
        ['id' => 2, 'name' => null, 'url' => 'http://php.test/abstract'],
        ['id' => 3, 'name' => 'handling-errors-best-practices', 'url' => 'http://programming.test/abstract/handling-errors/best-pratices'],
        ['id' => 4, 'name' => 'geocities', 'url' => 'geocities.test/abstract'],
    ];
    validityArray($abstracts);
    $markdownText = "# Header 1\n\nThis is a paragraph with [a link](http://example.com).\n\n- Bullet 1\n- Bullet 2\n\n1. Numbered 1\n2. Numbered 2\n\n> This is a blockquote\n\n```php\necho 'Hello, World!';\n```\n\n---\n";
    $converter = new MarkdownConverter($markdownText);
    $htmlContent = $converter->convert();
    echo $htmlContent;
    return 0;
}

main();