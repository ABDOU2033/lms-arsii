# 📚 LMS (Learning Management System) — PFE

Un système de gestion d'apprentissage (LMS) complet et fonctionnel construit avec **Laravel 10**, **MySQL**, et **Tailwind CSS**.

---

## 🎯 Objectif du projet

Créer une plateforme e-learning professionnelle où :

- **👨‍🏫 Enseignants** créent des cours, leçons, quiz et gèrent les résultats des étudiants
- **👨‍🎓 Étudiants** s'inscrivent, suivent les leçons, passent des quiz et suivent leur progression
- **👨‍💼 Administrateurs** gèrent les utilisateurs et voient les statistiques globales du système

---

## 📦 Prérequis

- **PHP** 8.1+ (testé avec 8.2.12)
- **Composer** (gestion des dépendances PHP)
- **MySQL** 5.7+ ou **MariaDB**
- **Node.js** 14+ (pour Vite/npm)
- **Git** (optionnel)

---

## 🚀 Installation rapide (3 minutes)

### 1️⃣ Ouvrir le répertoire du projet

```powershell
cd c:\Users\ABDO\Desktop\laravel\lms-arsii
```

### 2️⃣ Installer les dépendances

```powershell
composer install
npm install
```

### 3️⃣ Configurer l'environnement

```powershell
cp .env.example .env
php artisan key:generate
```

Ensuite, éditer `.env` avec vos paramètres MySQL :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_db
DB_USERNAME=root
DB_PASSWORD=

APP_URL=http://localhost:8000
```

### 4️⃣ Créer la base de données et les tables

```powershell
php artisan migrate
```

### 5️⃣ Peupler avec les données d'exemple

**Option A — DatabaseSeeder (recommandé)**

```powershell
php artisan db:seed
```

**Option B — Script PHP standalone**

```powershell
php scripts/seed.php
```

### 6️⃣ Démarrer les serveurs (2 terminaux)

**Terminal 1 — Laravel server:**

```powershell
php artisan serve --host=localhost --port=8000
```

**Terminal 2 — Vite (compilation assets):**

```powershell
npm run dev
```

### ✅ C'est prêt !

Ouvrir : **http://localhost:8000**

---

## 📝 Identifiants de connexion

Après le seeding, utilisez ces comptes de test :

| Rôle             | Email             | Mot de passe | Accès                                     |
| ---------------- | ----------------- | ------------ | ----------------------------------------- |
| **Admin**        | admin@lms.test    | password123  | Dashboard admin, Gestion des utilisateurs |
| **Professeur 1** | prof1@lms.test    | password123  | Créer cours, quiz, voir résultats         |
| **Professeur 2** | prof2@lms.test    | password123  | Créer cours, quiz, voir résultats         |
| **Étudiant 1**   | student1@lms.test | password123  | Consulter cours, passer quiz              |
| **Étudiant 2**   | student2@lms.test | password123  | Consulter cours, passer quiz              |
| **Étudiant 3**   | student3@lms.test | password123  | Consulter cours, passer quiz              |

---

## 🏗️ Architecture

### 📊 Schéma de base de données

```
Users (admin, teacher, student roles)
│
├── Courses (créés par les enseignants)
│   │
│   ├── Lessons (contenu pédagogique)
│   │   │
│   │   └── Quizzes (évaluation)
│   │       │
│   │       ├── Questions (avec réponses multiples)
│   │       │   └── Answers (options pour chaque question)
│   │       │
│   │       └── QuizAttempts (tentatives des étudiants)
│   │           └── AttemptAnswers (réponses soumises)
│   │
│   └── course_student (table de liaison)
│       ├── student_id
│       ├── course_id
│       ├── progress (0-100%)
│       └── enrolled_at (date inscription)
```

### 📁 Structure des répertoires clés

```
app/
  ├── Models/                          # Modèles Eloquent
  │   ├── User.php
  │   ├── Course.php
  │   ├── Lesson.php
  │   ├── Quiz.php
  │   ├── Question.php
  │   ├── Answer.php
  │   ├── QuizAttempt.php
  │   └── AttemptAnswer.php
  │
  └── Http/Controllers/
      ├── DashboardController.php      # Dashboard avec logique par rôle
      ├── CourseController.php         # CRUD cours + enroll/unenroll
      ├── LessonController.php         # Affichage leçons
      └── QuizController.php           # Quiz submission et scoring

database/
  ├── migrations/                      # Création des 8 tables
  └── seeders/
      └── DatabaseSeeder.php           # Population de données test

resources/
  ├── css/app.css                      # Tailwind CSS
  └── views/
      ├── layouts/                     # Layouts de base
      ├── auth/                        # Login/Register (Breeze)
      ├── courses/                     # Gestion cours
      ├── lessons/                     # Affichage leçons
      ├── quiz/                        # Quiz et questions
      └── dashboard/                   # Dashboards par rôle

routes/
  ├── web.php                          # Routes principales
  └── auth.php                         # Routes auth (Breeze)
```

---

## 🎮 Workflows utilisateurs

### 👨‍🏫 Workflow Enseignant

```
1. Connexion (prof1@lms.test)
        ↓
2. Dashboard → Voir mes cours et statistiques
        ↓
3. Créer un cours (Titre, Description, Catégorie)
        ↓
4. Ajouter des leçons (Contenu, Durée estimée)
        ↓
5. Ajouter un quiz avec questions (multiple choice / réponse courte)
        ↓
6. Voir les résultats des étudiants (Score, Temps, Réponses)
```

### 👨‍🎓 Workflow Étudiant

```
1. Connexion (student1@lms.test)
        ↓
2. Dashboard → Voir progression et cours inscrits
        ↓
3. Parcourir les cours publiés (/courses)
        ↓
4. Cliquer sur un cours → S'inscrire
        ↓
5. Lire les leçons du cours
        ↓
6. Passer le quiz et voir le score immédiatement
        ↓
7. Dashboard → Progression mise à jour (0-100%)
```

### 👨‍💼 Workflow Admin

```
1. Connexion (admin@lms.test)
        ↓
2. Dashboard → Statistiques globales
   - Total utilisateurs
   - Total cours publiés
   - Progression moyenne des étudiants
```

---

## 🛣️ Principales routes

### Routes publiques

```
GET  /                    → Page d'accueil
GET  /login               → Formulaire login
POST /login               → Soumettre login
```

### Routes authentifiées (protected par middleware `auth`)

```
GET  /dashboard           → Dashboard (par rôle)
GET  /courses             → Liste des cours publiés
GET  /courses/{id}        → Détails d'un cours
POST /courses/{id}/enroll → S'inscrire

GET  /courses/{course}/lessons/{lesson}           → Lire leçon
GET  /courses/{course}/lessons/{lesson}/quiz/{quiz} → Formulaire quiz
POST /courses/{course}/lessons/{lesson}/quiz/{quiz}/submit → Soumettre quiz
```

### Routes enseignants (prefix `/teacher`)

```
GET  /teacher/courses                 → Mes cours
POST /teacher/courses                 → Créer un cours
GET  /teacher/courses/create          → Formulaire création
GET  /teacher/courses/{id}/edit       → Éditer un cours
PUT  /teacher/courses/{id}            → Sauvegarder édition
DELETE /teacher/courses/{id}          → Supprimer
```

### Routes admin (prefix `/admin`)

```
GET  /admin/dashboard     → Dashboard admin
```

---

## ⚙️ Commandes utiles

### Migrations

```powershell
php artisan migrate                   # Exécuter migrations
php artisan migrate:fresh --seed      # Réinitialiser + peupler
```

### Seeding

```powershell
php artisan db:seed                   # Exécuter DatabaseSeeder
```

### Cache & Assets

```powershell
php artisan view:clear                # Effacer cache vues
php artisan cache:clear               # Effacer tout le cache
npm run dev                            # Démarrer Vite
npm run build                          # Compiler production
```

### Tinker (REPL PHP)

```powershell
php artisan tinker
  > User::all();
  > Course::with('teacher')->get();
  > Quiz::find(1)->questions;
```

---

## 🔐 Sécurité

✅ **CSRF Protection** — Tous les formulaires incluent `@csrf`
✅ **Password Hashing** — Utilise bcrypt
✅ **Authentication** — Laravel Breeze (session-based)
✅ **Role-based Access** — Middleware `teacher`, `admin`
✅ **Input Validation** — Validation serveur sur les inputs

---

## 🐛 Troubleshooting

### Erreur : `419 Page Expired` au login

```powershell
# Éditer .env (APP_URL = localhost:8000 ou 127.0.0.1:8000)
php artisan config:cache
php artisan view:clear
```

### Erreur : `Table already exists`

```powershell
php artisan migrate:fresh --seed
```

### Vues manquantes

→ Vérifier que les fichiers `.blade.php` existent dans `resources/views/`

### Assets non chargés

```powershell
npm run dev
```

---

## 📊 Données après seeding

**6 utilisateurs** : 1 Admin, 2 Enseignants, 3 Étudiants
**2 cours publiés** : "Introduction au Laravel", "Conception de BDD"
**Contenu** : 3 leçons, 1 Quiz avec 2 questions, 5 inscriptions

---

## 🎓 Checklist pour la soutenance

- [ ] Base de données migrée
- [ ] Données peuplées (php artisan db:seed)
- [ ] Serveurs lancés (Laravel + Vite)
- [ ] http://localhost:8000 accessible
- [ ] Connexion possible (test credentials)
- [ ] Dashboard affiche le rôle correct
- [ ] Étudiant peut s'inscrire à un cours
- [ ] Étudiant peut lire une leçon
- [ ] Étudiant peut passer un quiz et voir le score
- [ ] Professeur peut créer un cours
- [ ] Admin voit les stats globales

---

## 📚 Voir aussi

- [PFE_INSTRUCTIONS.md](PFE_INSTRUCTIONS.md) — Instructions détaillées, troubleshooting avancé
- [routes/web.php](routes/web.php) — Routes groupées par rôle
- [app/Models/](app/Models/) — Relations Eloquent
- [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php) — Données de test

---

**Bon développement et bonne soutenance ! 🎉**
