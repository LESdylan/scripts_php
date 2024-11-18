<?php
class CommentCollection {
    private array $comments = [];
    
    public function addComment(Commentaire $commentaire): void {
        $this->comments[] = $commentaire;
    }

    public function getComments(): array {
        return $this->comments;
    }

    public function getAverageRating(): float {
        if (count($this->comments) === 0) {
            return 0;
        }
        $total = 0;
        foreach ($this->comments as $comment) {
            $total += $comment->getRating();
        }
        return $total / count($this->comments);
    }
}

// Classe Entity : classe de base pour gérer des entités avec une liste
class Entity
{
    protected array $entities = [];  // Liste des entités (movies, commentaires)

    // Ajouter une entité à la liste
    public function addEntity($entity): void
    {
        $this->entities[] = $entity;
    }

    // Obtenir toutes les entités
    public function getEntities(): array
    {
        return $this->entities;
    }

    // Utilisation d'une fonction anonyme pour filtrer des entités
    public function filterEntities(callable $callback): array
    {
        return array_filter($this->entities, $callback);
    }

    // Utilisation d'une fonction fléchée pour trier des entités par note
    public function sortEntitiesByRating(): void
    {
        usort($this->entities, fn($a, $b) => $a->getRating() <=> $b->getRating());
    }

    // Utilisation d'une fonction variable pour afficher des entités selon un critère
    public function displayEntitiesWithCriterion(string $functionName): void
    {
        if (method_exists($this, $functionName)) {
            $this->$functionName();  // Appeler la fonction de manière dynamique
        }
    }
}

// Classe Commentaire : représente un avis sur un film.
class Commentaire
{
    private string $initials;
    private float $rating;
    private ?string $description;  // Description optionnelle
    private int $review_date;      // Timestamp pour la date de l'avis

    // Convertir un nom complet en initiales
    private function convertToInitials(string $name): string
    {
        $nameParts = explode(' ', $name);
        $initials = '';
        foreach ($nameParts as $part) {
            $initials .= strtoupper($part[0]);  // Prendre la première lettre de chaque partie du nom
        }
        return $initials;
    }

    private function validateRating(float $rating): void {
        if ($rating < 0 || $rating > 10) {
            throw new InvalidArgumentException('Rating must be between 0 and 10');
        }
    }

    public function __construct(string $name, float $rating, ?string $description = null, int $review_date = null) {
        $this->validateRating($rating); // Validation de la note
        $this->initials = $this->convertToInitials($name);
        $this->rating = $rating;
        $this->description = $description;
        $this->review_date = $review_date ?? time();
    }

    // Getters pour récupérer les données
    public function getInitials(): string
    {
        return $this->initials;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getReviewDate(): int
    {
        return $this->review_date;
    }
}

// Classe Movie : représente un film avec ses informations et ses commentaires.
// Classe Movie : représente un film avec ses informations et ses commentaires.
class Movie extends Entity
{
    private string $movie_name;
    private int $release_date;
    private CommentCollection $commentCollection;

    public function __construct(string $movie_name, int $release_date = null) {
        $this->movie_name = $movie_name;
        $this->release_date = $release_date ?? time();
        $this->commentCollection = new CommentCollection();
    }

    public function addComment(Commentaire $commentaire): void {
        $this->commentCollection->addComment($commentaire);
    }

    public function getComments(): array {
        return $this->commentCollection->getComments();
    }

    public function getAverageRating(): float {
        return $this->commentCollection->getAverageRating();
    }

    // Ajout de la méthode pour récupérer le nom du film
    public function getMovieName(): string {
        return $this->movie_name;
    }

    public function getReleaseDate(): int {
        return $this->release_date;
    }
}


// Classe Collection_Movies : gère une collection de films.
class Collection_Movies extends Entity
{
    // Ajouter un film à la collection
    public function addMovie(Movie $movie): void
    {
        $this->addEntity($movie);  // Ajouter un film à la collection
    }

    // Obtenir tous les films
    public function getMovies(): array
    {
        return $this->getEntities();  // Utiliser la méthode de la classe Entity pour obtenir les films
    }

    // Afficher les films et leurs évaluations moyennes
    public function displayMoviesWithRatings(): void
    {
        foreach ($this->getMovies() as $movie) {
            echo "Movie: " . $movie->getMovieName() . "\n";
            echo "Release Date: " . date('Y-m-d', $movie->getReleaseDate()) . "\n";
            echo "Average Rating: " . $movie->getAverageRating() . "\n";
            echo "\n";
        }
    }
}

// Créer des commentaires
$commentaire1 = new Commentaire('Alice Smith', 8.5, 'Great movie!', strtotime('2023-06-15'));
$commentaire2 = new Commentaire('Bob Johnson', 7.0, 'Interesting but could be better.', strtotime('2023-07-01'));
$commentaire3 = new Commentaire('Charlie Brown', 9.0, 'Absolutely amazing!', strtotime('2023-07-20'));

// Créer un film et ajouter des commentaires
$movie1 = new Movie('Inception');
$movie1->addComment($commentaire1);
$movie1->addComment($commentaire2);
$movie1->addComment($commentaire3);

// Créer une collection de films
$collection = new Collection_Movies();
$collection->addMovie($movie1);

// Afficher les films avec leurs évaluations moyennes
$collection->displayMoviesWithRatings();

// Afficher les initiales des commentateurs
echo "Comments with Initials: \n";
foreach ($movie1->getComments() as $comment) {
    echo $comment->getInitials() . ": " . $comment->getRating() . " - " . $comment->getDescription() . "\n";
}

// Utilisation de la fonction anonyme pour filtrer les commentaires de note > 8
$filteredComments = $movie1->filterEntities(function($commentaire) {
    return $commentaire->getRating() > 8;
});
echo "Filtered Comments (Rating > 8): \n";
foreach ($filteredComments as $comment) {
    echo $comment->getInitials() . ": " . $comment->getRating() . " - " . $comment->getDescription() . "\n";
}

// Utilisation de la fonction fléchée pour trier les films par note moyenne
$collection->sortEntitiesByRating();

// Utilisation de la fonction variable pour afficher un critère spécifique
$collection->displayEntitiesWithCriterion('displayMoviesWithRatings');
?>
