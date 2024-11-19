architecture/
├── app/                    # Code principal de l'application
│   ├── Controllers/        # Logique métier (contrôleurs)
│   ├── Helpers/            # Fonctions utilitaires
│   ├── Models/             # Logique des données et interaction avec la BDD
│   ├── Services/           # Services spécifiques (API tierces, paiement, etc.)
│   └── Views/              # Templates et pages HTML/PHP
│
├── classes/                # Classes réutilisables
│   ├── Product.php         # Classe pour la gestion des produits
│   └── User.php            # Classe pour la gestion des utilisateurs
│
├── config/                 # Configurations globales
│   ├── .env                # Variables d'environnement (non versionnées)
│   ├── app.php             # Config de l'application (nom, langue, etc.)
│   └── database.php        # Config de la base de données
│
├── include/                # Éléments réutilisables pour les vues
│   ├── footer.php          # Pied de page
│   ├── header.php          # En-tête
│   └── navigation.php      # Barre de navigation (ajoutée pour la navigation)
│
├── public/                 # Fichiers accessibles publiquement (racine web)
│   ├── index.php           # Point d'entrée principal
│   ├── .htaccess           # Règles Apache pour réécrire les URLs
│   └── assets/             # Fichiers statiques (CSS, JS, images)
│       ├── css/            # Fichiers CSS
│       ├── images/         # Images du site
│       └── js/             # Fichiers JavaScript
│
├── storage/                # Données générées par l'application
│   ├── logs/               # Fichiers de logs
│   └── uploads/            # Fichiers téléchargés par les utilisateurs
│
├── tests/                  # Tests automatisés
│   ├── Integration/        # Tests d'intégration
│   └── Unit/               # Tests unitaires
│
├── vendor/                 # Dépendances externes (gérées par Composer)
│
└── composer.json           # Fichier de configuration Composer


## couplage faible
> séparer l'affichage des calculs
