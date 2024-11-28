<?php

/**
 * Classe de base représentant une personne.
 * 
 * Cette classe utilise les concepts d'encapsulation pour protéger les données,
 * et peut être héritée par d'autres classes spécialisées (ex : Client, Admin).
 */
class Person {
    // Propriétés
    private string $firstName;
    private string $lastName;
    private string $email;
    protected string $phoneNumber;

    /**
     * Constructeur de la classe Person.
     *
     * @param string $firstName Le prénom de la personne.
     * @param string $lastName Le nom de la personne.
     * @param string $email L'adresse email de la personne.
     * @param string $phoneNumber Le numéro de téléphone de la personne.
     */
    public function __construct(string $firstName, string $lastName, string $email, string $phoneNumber) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Méthode magique pour afficher les informations de la personne.
     *
     * @return string Les informations sous forme de chaîne.
     */
    public function __toString(): string {
        return "{$this->firstName} {$this->lastName}, Email: {$this->email}";
    }

    // Getters et setters
    public function getFirstName(): string {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    protected function getPhoneNumber(): string {
        return $this->phoneNumber;
    }

    protected function setPhoneNumber(string $phoneNumber): void {
        $this->phoneNumber = $phoneNumber;
    }
    //MÉTHODES MAGIQUES
    public function __get($propriete) {
        if (property_exists($this, $propriete)) {
            return $this->$propriete;
        }
    }

    public function __set($propriete, $valeur) {
        if (property_exists($this, $propriete)) {
            $this->$propriete = $valeur;
        }
    }
}

/**
 * Classe Client héritant de la classe Person.
 * 
 * Représente un client avec des propriétés et des méthodes spécifiques.
 */
class Client extends Person {
    // Propriétés spécifiques à un client
    private int $clientId;
    private string $status;

    /**
     * Constructeur de la classe Client.
     *
     * @param string $firstName Le prénom du client.
     * @param string $lastName Le nom du client.
     * @param string $email L'adresse email du client.
     * @param string $phoneNumber Le numéro de téléphone du client.
     * @param int $clientId L'identifiant unique du client.
     * @param string $status Le statut du client (actif, inactif, etc.).
     */
    public function __construct(
        string $firstName, 
        string $lastName, 
        string $email, 
        string $phoneNumber, 
        int $clientId, 
        string $status
    ) {
        parent::__construct($firstName, $lastName, $email, $phoneNumber);
        $this->clientId = $clientId;
        $this->status = $status;
    }

    // Getters et setters pour les propriétés spécifiques
    public function getClientId(): int {
        return $this->clientId;
    }

    public function setClientId(int $clientId): void {
        $this->clientId = $clientId;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    /**
     * Méthode pour afficher les détails du client.
     *
     * @return string Les détails du client.
     */
    public function getDetails(): string {
        return parent::__toString() . ", Client ID: {$this->clientId}, Status: {$this->status}";
    }
}

// Utilisation des classes
$client = new Client(
    "John", 
    "Doe", 
    "john.doe@example.com", 
    "123456789", 
    101, 
    "Actif"
);

echo $client->getDetails();

?>
