# GUIDE COMPLET - COMMANDES POUR DÉMARRER LE LMS

## 1️⃣ INSTALLATION INITIALE

### Créer le projet (déjà fait)

```bash
cd c:\Users\ABDO\Desktop\laravel\lms-arsii
```

### Installer les dépendances

```bash
composer install
npm install
```

### Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

### Configurer la base de données (.env)

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_arsii
DB_USERNAME=root
DB_PASSWORD=
```

### Créer la base de données MySQL

```bash
mysql -u root
CREATE DATABASE lms_arsii;
EXIT;
```

### Exécuter les migrations

```bash
php artisan migrate:fresh
```

---

## 2️⃣ DÉMARRER LE PROJET

### Terminal 1: Serveur Laravel

```bash
php artisan serve
# Accédez à: http://localhost:8000
```

### Terminal 2: Compilation des assets (Vue/React)

```bash
npm run dev
```

### Pour la production

```bash
npm run build
```

---

## 3️⃣ CRÉER LES DONNÉES DE TEST AVEC TINKER

### Démarrer Tinker

```bash
php artisan tinker
```

### Exécuter ces commandes une par une

#### Créer un admin

```php
use App\Models\User;
User::create([
    'name' => 'Admin',
    'email' => 'admin@lms.test',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

#### Créer 2 enseignants

```php
User::create([
    'name' => 'Prof Informatique',
    'email' => 'prof1@lms.test',
    'password' => bcrypt('password'),
    'role' => 'teacher',
    'specialization' => 'Informatique'
]);

User::create([
    'name' => 'Prof Maths',
    'email' => 'prof2@lms.test',
    'password' => bcrypt('password'),
    'role' => 'teacher',
    'specialization' => 'Mathématiques'
]);
```

#### Créer 10 étudiants

```php
for ($i = 1; $i <= 10; $i++) {
    User::create([
        'name' => "Étudiant $i",
        'email' => "student$i@lms.test",
        'password' => bcrypt('password'),
        'role' => 'student'
    ]);
}
```

#### Créer des cours

```php
use App\Models\Course;

$teacher1 = User::find(2); // Prof Informatique
$teacher2 = User::find(3); // Prof Maths

Course::create([
    'title' => 'Introduction à PHP',
    'slug' => 'intro-php',
    'description' => 'Apprenez PHP de zéro',
    'teacher_id' => $teacher1->id,
    'category' => 'Informatique',
    'level' => 'beginner',
    'price' => 0,
    'is_published' => true
]);

Course::create([
    'title' => 'Laravel Avancé',
    'slug' => 'laravel-avance',
    'description' => 'Concepts avancés de Laravel',
    'teacher_id' => $teacher1->id,
    'category' => 'Informatique',
    'level' => 'advanced',
    'price' => 49.99,
    'is_published' => true
]);

Course::create([
    'title' => 'Algèbre Linéaire',
    'slug' => 'algebre',
    'description' => 'Les bases de l\'algèbre',
    'teacher_id' => $teacher2->id,
    'category' => 'Mathématiques',
    'level' => 'intermediate',
    'price' => 29.99,
    'is_published' => true
]);
```

#### Inscrire les étudiants aux cours

```php
$students = User::where('role', 'student')->get();
$courses = Course::all();

foreach ($students as $student) {
    $randomCourses = $courses->random(rand(1, 3));
    foreach ($randomCourses as $course) {
        $student->studentCourses()->attach($course->id, [
            'progress' => rand(0, 100),
            'enrolled_at' => now()
        ]);
    }
}
```

#### Créer des leçons

```php
use App\Models\Lesson;

$course1 = Course::find(1);

Lesson::create([
    'course_id' => $course1->id,
    'title' => 'Syntaxe PHP',
    'description' => 'Bases de la syntaxe',
    'content' => 'Apprenez la syntaxe de base du PHP',
    'order' => 1,
    'is_free' => true,
    'video_url' => 'https://youtube.com/embed/video1'
]);

Lesson::create([
    'course_id' => $course1->id,
    'title' => 'Boucles et conditions',
    'description' => 'Contrôle du flux',
    'content' => 'Apprenez les structures de contrôle',
    'order' => 2,
    'is_free' => false,
    'video_url' => 'https://youtube.com/embed/video2'
]);
```

#### Créer des quiz

```php
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

$lesson1 = Lesson::find(1);

$quiz = Quiz::create([
    'lesson_id' => $lesson1->id,
    'title' => 'Quiz PHP Basique',
    'description' => 'Testez vos connaissances',
    'passing_score' => 70,
    'time_limit' => 30
]);

// Créer une question
$q1 = Question::create([
    'quiz_id' => $quiz->id,
    'question_text' => 'Quel est le type de 3.14?',
    'question_type' => 'multiple_choice',
    'order' => 1
]);

// Créer les réponses
Answer::create(['question_id' => $q1->id, 'answer_text' => 'String', 'is_correct' => false, 'order' => 1]);
Answer::create(['question_id' => $q1->id, 'answer_text' => 'Float', 'is_correct' => true, 'order' => 2]);
Answer::create(['question_id' => $q1->id, 'answer_text' => 'Integer', 'is_correct' => false, 'order' => 3]);
```

#### Créer une tentative de quiz

```php
use App\Models\QuizAttempt;
use App\Models\AttemptAnswer;

$student = User::find(4); // Étudiant 1
$quiz = Quiz::find(1);

$attempt = QuizAttempt::create([
    'quiz_id' => $quiz->id,
    'student_id' => $student->id,
    'started_at' => now()->subHour(),
    'finished_at' => now(),
    'score' => 85
]);

// Enregistrer les réponses de l'étudiant
AttemptAnswer::create([
    'quiz_attempt_id' => $attempt->id,
    'question_id' => 1,
    'selected_answer_id' => 2, // Float - correct
    'is_correct' => true
]);
```

---

## 4️⃣ COMPTES DE TEST À UTILISER

```
Administrateur:
Email: admin@lms.test
Mot de passe: password

Enseignant:
Email: prof1@lms.test
Mot de passe: password

Étudiant:
Email: student1@lms.test
Mot de passe: password
```

---

## 5️⃣ FICHIERS CLÉS À COMPRENDRE

### Backend

- `app/Models/User.php` - Modèle utilisateur
- `app/Models/Course.php` - Modèle cours
- `app/Models/Lesson.php` - Modèle leçon
- `app/Models/Quiz.php` - Modèle quiz
- `app/Http/Controllers/DashboardController.php` - Tableau de bord
- `app/Http/Controllers/CourseController.php` - Gestion des cours
- `routes/web.php` - Définition des routes

### Frontend

- `resources/views/layouts/app.blade.php` - Layout principal
- `resources/views/dashboard/` - Pages du tableau de bord
- `resources/views/courses/` - Pages des cours
- `resources/views/lessons/` - Pages des leçons

---

## 6️⃣ COMMANDES UTILES

### Créer un nouveau contrôleur

```bash
php artisan make:controller NomController
```

### Créer un nouveau modèle avec migration

```bash
php artisan make:model NomModele -m
```

### Créer une migration seule

```bash
php artisan make:migration create_table_name
```

### Rollback des migrations

```bash
php artisan migrate:rollback
```

### Vider la base et recommencer

```bash
php artisan migrate:fresh
```

### Lancer les tests

```bash
php artisan test
```

### Nettoyer les caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 7️⃣ STRUCTURE DES DOSSIERS

```
lms-arsii/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── CourseController.php
│   │   ├── LessonController.php
│   │   ├── QuizController.php
│   │   └── ...
│   ├── Models/
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── Lesson.php
│   │   ├── Quiz.php
│   │   └── ...
│   └── ...
├── routes/
│   ├── web.php (routes principales)
│   ├── api.php (routes API)
│   └── auth.php (routes d'authentification)
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── dashboard/
│   │   ├── courses/
│   │   ├── lessons/
│   │   └── ...
│   ├── css/
│   └── js/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── storage/
├── vendor/
├── .env (à créer)
└── package.json
```

---

## 8️⃣ TIPS DE DÉVELOPPEMENT

### Afficher les erreurs SQL

```php
// Dans .env
APP_DEBUG=true
```

### Utiliser la base de données de test

```bash
php artisan migrate --env=testing
```

### Vérifier les routes

```bash
php artisan route:list
```

### Recompiler les assets lors des modifications

```bash
npm run dev
```

### Vérifier les erreurs de code

```bash
php artisan tinker
# Puis taper du code à tester
```

---

## ✅ CHECKLIST DE VÉRIFICATION

- [x] Base de données créée
- [ ] Migrations exécutées
- [ ] Données de test créées
- [ ] Serveur Laravel lancé
- [ ] Assets compilés
- [ ] Peut accéder à http://localhost:8000
- [ ] Peut se connecter avec admin@lms.test
- [ ] Peut voir le tableau de bord
- [ ] Peut créer un cours (en tant qu'enseignant)
- [ ] Peut s'inscrire à un cours (en tant qu'étudiant)
- [ ] Peut voir les leçons
- [ ] Peut répondre aux quiz
