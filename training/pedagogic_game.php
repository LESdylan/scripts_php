<?php
class Users
{
    protected array $list = 
    [
        'Adam' => 8,
        'Julie' => 13,
        'Karima' => 11,
        'Anna' => 11,
        'Marina' => 9,
        'Mohamed' => 7,
        'Arthur' => 12,
        'Morgan' => 14
    ];

    // Ajouter des utilisateurs à la liste
    public function addUsers(array $new_users): void
    {
        // Définir la fonction anonyme pour vérifier si un utilisateur existe déjà
        $verify_doublon = function(string $name) {
            return isset($this->list[$name]);
        };

        foreach ($new_users as $name => $age) {
            // Vérifier si l'utilisateur existe déjà en utilisant la fonction anonyme
            if (!$verify_doublon($name)) {
                $this->list[$name] = $age; // Ajouter seulement s'il n'existe pas
            } else {
                echo "L'utilisateur $name existe déjà.\n";
            }
        }
    }

    // Retourner la liste des utilisateurs
    public function getUsers(): array
    {
        return $this->list;
    }

    // Utiliser array_filter pour séparer les utilisateurs en deux groupes
    public function getGroupUser($boundaryAge): array
    {
        asort($this->list); // Trie la liste par âge

        // Filtrer les utilisateurs avec age < boundaryAge
        $tenLess = array_filter($this->list, fn($age) => $age < $boundaryAge);

        // Filtrer les utilisateurs avec age >= boundaryAge
        $tenMore = array_filter($this->list, fn($age) => $age >= $boundaryAge);

        // Retourner les deux groupes
        return ['tenMore' => $tenMore, 'tenLess' => $tenLess];
    }
}

// Créer une instance de la classe Users
$userManager = new Users();

// Exemple de nouveaux utilisateurs à ajouter
$newUsers = [
    'Adam' => 10,
    'Hector' => 6,
    'Manon' => 8,
    'Elisa' => 10,
    'Leo' => 12,
    'Enzo' => 13,
    'Ada' => 9
];

// Ajouter les nouveaux utilisateurs
$userManager->addUsers($newUsers);

// Obtenir les utilisateurs dans les groupes
$boundaryAge = 10;
$groups = $userManager->getGroupUser($boundaryAge);

// Afficher les groupes d'utilisateurs
print_r($groups);
