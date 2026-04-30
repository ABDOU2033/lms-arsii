<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$content = <<<EOT
# Installation et Configuration de Laravel 11

## 1. Prérequis

Avant d'installer Laravel, assurez-vous d'avoir :

- **PHP 8.2 ou supérieur** : Vérifiez avec `php -v`
- **Composer** : Gestionnaire de dépendances PHP (téléchargez sur getcomposer.org)
- **Node.js et NPM** : Pour la compilation des assets frontend
- **Une base de données** : MySQL, PostgreSQL, SQLite ou MariaDB
- **Un serveur web** : Apache ou Nginx (ou utilisez le serveur intégré de Laravel)

## 2. Installation de Laravel

### Méthode 1 : Via Composer (Recommandée)

```bash
composer create-project laravel/laravel mon-projet
```

Cette commande :
- Télécharge Laravel et toutes ses dépendances
- Crée la structure du projet
- Génère automatiquement la clé d'application
- Crée le fichier `.env`

### Méthode 2 : Via Laravel Installer

```bash
composer global require laravel/installer
laravel new mon-projet
```

## 3. Configuration Initiale

### 3.1. Fichier .env

Après l'installation, configurez le fichier `.env` à la racine du projet :

```
APP_NAME=MonApplication
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_votre_base
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 3.2. Clé d'Application

Si la clé n'est pas générée :

```bash
php artisan key:generate
```

### 3.3. Permissions des Dossiers

Sur Linux/Mac :

```bash
chmod -R 775 storage bootstrap/cache
```

## 4. Configuration de la Base de Données

### Créer une base MySQL :

```sql
CREATE DATABASE nom_de_votre_base CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Exécuter les migrations :

```bash
php artisan migrate
```

Cette commande crée les tables par défaut :
- `users` : Gestion des utilisateurs
- `password_reset_tokens` : Réinitialisation de mot de passe
- `sessions` : Gestion des sessions
- `cache` et `cache_locks` : Système de cache
- `jobs`, `job_batches`, `failed_jobs` : File d'attente

## 5. Démarrer le Serveur de Développement

```bash
php artisan serve
```

L'application sera accessible à : **http://localhost:8000**

## 6. Structure du Projet Laravel

```
mon-projet/
├── app/
│   ├── Http/Controllers/    # Contrôleurs
│   ├── Models/              # Modèles Eloquent
│   └── Providers/           # Providers de services
├── config/                  # Fichiers de configuration
├── database/
│   ├── migrations/          # Fichiers de migration
│   └── seeders/             # Seeders de données
├── public/                  # Point d'entrée (index.php)
├── resources/
│   ├── views/               # Templates Blade
│   └── js/                  # Assets JavaScript
├── routes/
│   ├── web.php              # Routes web
│   └── api.php              # Routes API
├── storage/                 # Logs, cache, uploads
└── tests/                   # Tests unitaires
```

## 7. Vérification de l'Installation

1. Ouvrez votre navigateur à `http://localhost:8000`
2. Vous devriez voir la page d'accueil par défaut de Laravel
3. Testez Artisan : `php artisan --version`

## 8. Configuration Avancée

### Mode Debug

En développement :
```
APP_DEBUG=true
```

En production :
```
APP_DEBUG=false
```

### Cache de Configuration

Pour optimiser les performances :

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Points Clés à Retenir

✅ Laravel nécessite PHP 8.2+
✅ Composer est indispensable pour l'installation
✅ Le fichier `.env` contient toute la configuration sensible
✅ `php artisan migrate` crée la structure de la base de données
✅ Le serveur de développement se lance avec `php artisan serve`
✅ La structure MVC sépare la logique métier, les données et l'interface
EOT;

DB::table('lecons')->where('id', 70)->update(['contenu' => $content]);

echo "✅ Lesson content updated successfully!\n";
echo "Content length: " . strlen($content) . " characters\n";
