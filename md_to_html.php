<?php

class MarkdownConverter
{
    private ?string $markdownFile;
    private ?string $markdownText;

    // Le constructeur peut accepter du texte brut Markdown ou un fichier Markdown.
    public function __construct(?string $markdownText = null, ?string $markdownFile = null)
    {
        $this->markdownFile = $markdownFile;
        $this->markdownText = $markdownText;
    }

    // Convertir les titres Markdown (ex: # Titre 1) en balises HTML
    private function cvt_headings($mdText)
    {
        $mdText = preg_replace('/^#{1} (.*)$/m', '<h1>$1</h1>', $mdText); // Titre h1
        $mdText = preg_replace('/^#{2} (.*)$/m', '<h2>$1</h2>', $mdText); // Titre h2
        $mdText = preg_replace('/^#{3} (.*)$/m', '<h3>$1</h3>', $mdText); // Titre h3
        $mdText = preg_replace('/^#{4} (.*)$/m', '<h4>$1</h4>', $mdText); // Titre h4
        $mdText = preg_replace('/^#{5} (.*)$/m', '<h5>$1</h5>', $mdText); // Titre h5
        $mdText = preg_replace('/^#{6} (.*)$/m', '<h6>$1</h6>', $mdText); // Titre h6
        return $mdText;
    }

    // Convertir les liens Markdown [text](url) en balises <a href="url">text</a>
    private function cvt_links($mdText)
    {
        $mdText = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $mdText);
        return $mdText;
    }

    // Convertir les listes à puces Markdown (utilisant "*" ou "-") en balises <ul> et <li>
    private function cvt_bulletedList($mdText)
    {
        // Remplacer les lignes qui commencent par * ou - en une liste <ul><li></li></ul>
        $mdText = preg_replace('/^(\*|-) (.*)$/m', '<ul><li>$2</li></ul>', $mdText);
        // Regrouper les éléments de la liste dans une seule balise <ul>
        $mdText = preg_replace('/(<ul><li>.*?<\/li><\/ul>)+/', '<ul>$0</ul>', $mdText);
        return $mdText;
    }

    // Convertir les listes numérotées Markdown (ex: 1. Premier, 2. Deuxième) en balises <ol> et <li>
    private function cvt_numberedList($mdText)
    {
        $mdText = preg_replace('/^\d+\. (.*)$/m', '<ol><li>$1</li></ol>', $mdText);
        // Regrouper les éléments de la liste dans une seule balise <ol>
        $mdText = preg_replace('/(<ol><li>.*?<\/li><\/ol>)+/', '<ol>$0</ol>', $mdText);
        return $mdText;
    }

    // Convertir le texte Markdown en HTML complet
    public function convert(): string
    {
        // Étapes de conversion successives du texte Markdown
        $mdText = $this->markdownText;

        // Appliquer les différentes conversions
        $mdText = $this->cvt_headings($mdText);
        $mdText = $this->cvt_BoldStrikeItalic($mdText);
        $mdText = $this->cvt_links($mdText);
        $mdText = $this->cvt_bulletedList($mdText);
        $mdText = $this->cvt_numberedList($mdText);

        return $mdText;
    }

    // Fonction pour charger le contenu d'un fichier Markdown
    public function loadFromFile(): void
    {
        if ($this->markdownFile && file_exists($this->markdownFile)) {
            $this->markdownText = file_get_contents($this->markdownFile);
        } else {
            throw new Exception('Fichier Markdown introuvable.');
        }
    }

    // Fonction pour convertir le texte en gras, italique, barré, et code
    private function cvt_BoldStrikeItalic($mdText)
    {
        // Remplacer ** ou __ par des balises <strong> pour le texte en gras
        $mdText = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $mdText);
        $mdText = preg_replace('/__(.*?)__/', '<strong>$1</strong>', $mdText);

        // Remplacer * ou _ par des balises <em> pour le texte en italique
        $mdText = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $mdText);
        $mdText = preg_replace('/_(.*?)_/', '<em>$1</em>', $mdText);

        // Remplacer ~~ par des balises <del> pour le texte barré
        $mdText = preg_replace('/~~(.*?)~~/', '<del>$1</del>', $mdText);

        // Remplacer les backticks ` pour les codes en ligne
        $mdText = preg_replace('/`(.*?)`/', '<code>$1</code>', $mdText);

        return $mdText;
    }
}

// Charger un fichier Markdown et le convertir
$converter = new MarkdownConverter(null, '.\md_to_html.md');
$converter->loadFromFile();
$htmlContent = $converter->convert();

// Afficher le résultat HTML
echo $htmlContent;

//    
//    private function cvt_olist()
//    {
//
//    }
//    private function cvt_olist()
//    {
//
//    }
//    private function cvt_links()
//    {
//
//    }
//    private function images()
//    {
//
//    }
//    private functions blockquote()
//    {
//
//    }
//    private functions lineCode()
//    {
//
//    }
//    private function blockCode()
//    {
//
//    }
//    private function separators()
//    {
//
//    }
//    private function cvt_tables()
//    {
//
//    }
//    private function specChar()
//    {
//
//    }