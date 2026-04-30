# LMS PFE - Instructions d'installation et de test

Ce document décrit la structure minimale du projet, comment lancer les migrations, peupler la base (Tinker / script), et les identifiants de test.

## Structure clé du projet

- `app/Models` : modèles Eloquent (`User`, `Course`, `Lesson`, `Quiz`, `Question`, `Answer`, etc.)
- `app/Http/Controllers` : contrôleurs (Dashboard, Course, Lesson, Quiz, Auth)
- `database/migrations` : migrations de la base
- `resources/views` : vues Blade (`layouts`, `auth`, `courses`, `lessons`, `quiz`, `dashboard`)
- `scripts/seed.php` : script d'initialisation (voir ci-dessous)

## Prérequis

- PHP 8.1+ (vous avez 8.2.12)
- Composer installé
- Base MySQL configurée dans `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- `APP_URL` dans `.env` doit correspondre à l'URL utilisée (ex. `http://localhost:8000`)

## Commandes d'installation (depuis la racine du projet)

1. Installer dépendances :

```powershell
composer install
npm install
npm run build   # ou npm run dev en dev
```

2. Générer la clé et configurer `.env` si nécessaire :

```powershell
cp .env.example .env
php artisan key:generate
```

3. Lancer les migrations :

```powershell
php artisan migrate
```

## Peupler la base (2 options)

Option A — script PHP (recommandé localement)

```powershell
php scripts/seed.php
```

Ce script bootstrappe l'application et crée :

- Utilisateurs : `admin@lms.test`, `prof1@lms.test`, `student1@lms.test` (mot de passe : `password123`)
- Un cours, une leçon, un quiz, 1 question, 2 réponses

Option B — artisan tinker (une ligne)

```powershell
php artisan tinker --execute="// voir section Tinker ci-dessous"
```

Tinker (exemple rapide)

```php
use App\\Models\\User; use App\\Models\\Course; use Illuminate\\Support\\Str;
User::create(['name'=>'Admin','email'=>'admin@lms.test','password'=>bcrypt('password123'),'role'=>'admin']);
// créer professeur, étudiant, cours, leçon, quiz, question, réponses
```

## Script `scripts/seed.php`

Exécute `php scripts/seed.php`. Il bootstrappe Laravel et insère les données de test.

## Identifiants de test

- Admin: email `admin@lms.test` / mot de passe `password123`
- Professeur: `prof1@lms.test` / `password123`
- Étudiant: `student1@lms.test` / `password123`

## Importer un fichier SQL (si vous préférez)

Si vous avez un dump SQL (fichier `.sql`), importez-le par :

```powershell
mysql -u DB_USERNAME -p DB_DATABASE < sample_data.sql
```

## Résolution des problèmes courants

- Si vous obtenez `419 Page Expired` lors du login : assurez-vous d'utiliser le même host que `APP_URL` (localhost vs 127.0.0.1). Effacez les cookies du navigateur et supprimez les sessions côté serveur :

```powershell
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
Remove-Item storage\\framework\\sessions\\* -Force
```

- Si une vue manque (ex. `View [courses.index] not found`) : vérifiez `resources/views/courses/index.blade.php` existe.

## Tests rapides

1. Démarrer le serveur : `php artisan serve --host=localhost --port=8000`
2. Ouvrir `http://localhost:8000` puis login avec `admin@lms.test` / `password123`.

---

Si vous voulez, j'ajoute un `DatabaseSeeder` et un `php artisan db:seed` automatique. Voulez-vous que je l'ajoute maintenant ?
