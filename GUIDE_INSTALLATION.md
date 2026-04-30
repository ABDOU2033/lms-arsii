# LMS ARSII - Guide Complet d'Installation et de Configuration

## 1. INSTALLATION ET CONFIGURATION INITIALE

### 1.1 Cloner le projet et installer les dépendances

```bash
cd c:\Users\ABDO\Desktop\laravel\lms-arsii
composer install
npm install
```

### 1.2 Créer le fichier .env et générer la clé

```bash
cp .env.example .env
php artisan key:generate
```

### 1.3 Configurer la base de données dans .env

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_arsii
DB_USERNAME=root
DB_PASSWORD=
```

### 1.4 Créer la base de données

```bash
mysql -u root -p
CREATE DATABASE lms_arsii;
EXIT;
```

### 1.5 Exécuter les migrations

```bash
php artisan migrate:fresh --seed
```

---

## 2. STRUCTURE DES MODÈLES

### Relations:

- User -> Teacher (hasMany Courses) / Student (belongsToMany Courses)
- Course -> Teacher (belongsTo), Students (belongsToMany), Lessons (hasMany)
- Lesson -> Course (belongsTo), Quiz (hasOne), Attachments (hasMany)
- Quiz -> Lesson (belongsTo), Questions (hasMany), Attempts (hasMany)
- Question -> Quiz (belongsTo), Answers (hasMany)
- QuizAttempt -> Student (belongsTo), Answers (hasMany)
- Answer -> Student, Question, QuizAttempt

---

## 3. COMMANDES TINKER - CRÉATION DES DONNÉES

```php
// Démarrer Tinker
php artisan tinker

// ============= ÉTAPE 1: CRÉER LES ADMINISTRATEURS =============
User::create([
    'name' => 'Admin',
    'email' => 'admin@lms.test',
    'password' => bcrypt('password'),
    'role' => 'admin',
    'phone' => '0600000000',
    'bio' => 'Administrateur du système'
]);

// ============= ÉTAPE 2: CRÉER LES ENSEIGNANTS =============
User::create([
    'name' => 'Prof Informatique',
    'email' => 'prof1@lms.test',
    'password' => bcrypt('password'),
    'role' => 'teacher',
    'phone' => '0611111111',
    'specialization' => 'Informatique',
    'bio' => 'Enseignant spécialisé en informatique'
]);

User::create([
    'name' => 'Prof Mathématiques',
    'email' => 'prof2@lms.test',
    'password' => bcrypt('password'),
    'role' => 'teacher',
    'phone' => '0622222222',
    'specialization' => 'Mathématiques',
    'bio' => 'Enseignant de mathématiques'
]);

// ============= ÉTAPE 3: CRÉER LES ÉTUDIANTS =============
for ($i = 1; $i <= 10; $i++) {
    User::create([
        'name' => "Étudiant $i",
        'email' => "student$i@lms.test",
        'password' => bcrypt('password'),
        'role' => 'student',
        'phone' => "0633333" . str_pad($i, 3, '0', STR_PAD_LEFT),
    ]);
}

// ============= ÉTAPE 4: CRÉER LES COURS =============
$teacher1 = User::where('email', 'prof1@lms.test')->first();
$teacher2 = User::where('email', 'prof2@lms.test')->first();

Course::create([
    'title' => 'Introduction à PHP',
    'slug' => 'introduction-php',
    'description' => 'Apprenez les bases du PHP moderne avec Laravel',
    'teacher_id' => $teacher1->id,
    'category' => 'Informatique',
    'level' => 'beginner',
    'price' => 0,
    'is_published' => true
]);

Course::create([
    'title' => 'Développement Web Avancé',
    'slug' => 'dev-web-avance',
    'description' => 'Apprenez les concepts avancés du développement web',
    'teacher_id' => $teacher1->id,
    'category' => 'Informatique',
    'level' => 'advanced',
    'price' => 49.99,
    'is_published' => true
]);

Course::create([
    'title' => 'Algèbre Linéaire',
    'slug' => 'algebre-lineaire',
    'description' => 'Concepts fondamentaux d\'algèbre linéaire',
    'teacher_id' => $teacher2->id,
    'category' => 'Mathématiques',
    'level' => 'intermediate',
    'price' => 29.99,
    'is_published' => true
]);

// ============= ÉTAPE 5: INSCRIRE LES ÉTUDIANTS AUX COURS =============
$courses = Course::all();
$students = User::where('role', 'student')->get();

foreach ($students as $student) {
    // Chaque étudiant s'inscrit à 2-3 cours aléatoirement
    $randomCourses = $courses->random(rand(2, 3));
    foreach ($randomCourses as $course) {
        $student->studentCourses()->attach($course->id, [
            'progress' => rand(0, 100),
            'enrolled_at' => now()
        ]);
    }
}

// ============= ÉTAPE 6: CRÉER LES LEÇONS =============
$course1 = Course::find(1);

Lesson::create([
    'course_id' => $course1->id,
    'title' => 'Les bases de PHP',
    'description' => 'Comprendre les variables et les types',
    'content' => 'La leçon couvre les variables, les types de données...',
    'order' => 1,
    'is_free' => true,
    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
]);

Lesson::create([
    'course_id' => $course1->id,
    'title' => 'Structures de contrôle',
    'description' => 'If, else, switch et boucles',
    'content' => 'Les structures de contrôle permettent...',
    'order' => 2,
    'is_free' => false,
    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
]);

Lesson::create([
    'course_id' => $course1->id,
    'title' => 'Fonctions en PHP',
    'description' => 'Définir et utiliser des fonctions',
    'content' => 'Les fonctions sont essentielles pour...',
    'order' => 3,
    'is_free' => false,
    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
]);

// ============= ÉTAPE 7: CRÉER LES QUIZ =============
$lesson1 = Lesson::find(1);

$quiz = Quiz::create([
    'lesson_id' => $lesson1->id,
    'title' => 'Quiz: Les bases de PHP',
    'description' => 'Testez vos connaissances sur les bases',
    'passing_score' => 70,
    'time_limit' => 30 // en minutes
]);

// ============= ÉTAPE 8: CRÉER LES QUESTIONS =============
Question::create([
    'quiz_id' => $quiz->id,
    'question_text' => 'Quel est le type de données pour 3.14?',
    'question_type' => 'multiple_choice',
    'order' => 1
]);

$q1 = Question::find(1);

Answer::create([
    'question_id' => $q1->id,
    'answer_text' => 'String',
    'is_correct' => false,
    'order' => 1
]);

Answer::create([
    'question_id' => $q1->id,
    'answer_text' => 'Float',
    'is_correct' => true,
    'order' => 2
]);

Answer::create([
    'question_id' => $q1->id,
    'answer_text' => 'Integer',
    'is_correct' => false,
    'order' => 3
]);

Answer::create([
    'question_id' => $q1->id,
    'answer_text' => 'Boolean',
    'is_correct' => false,
    'order' => 4
]);

// Créer une 2e question
Question::create([
    'quiz_id' => $quiz->id,
    'question_text' => 'Quelle est la syntaxe correcte pour une variable en PHP?',
    'question_type' => 'multiple_choice',
    'order' => 2
]);

$q2 = Question::find(2);

Answer::create([
    'question_id' => $q2->id,
    'answer_text' => 'var $x = 5;',
    'is_correct' => false,
    'order' => 1
]);

Answer::create([
    'question_id' => $q2->id,
    'answer_text' => '$x = 5;',
    'is_correct' => true,
    'order' => 2
]);

Answer::create([
    'question_id' => $q2->id,
    'answer_text' => 'x = 5;',
    'is_correct' => false,
    'order' => 3
]);

// ============= ÉTAPE 9: CRÉER LES TENTATIVES DE QUIZ =============
$student1 = User::where('email', 'student1@lms.test')->first();

$attempt = QuizAttempt::create([
    'quiz_id' => $quiz->id,
    'student_id' => $student1->id,
    'started_at' => now()->subHours(2),
    'finished_at' => now()->subHour(),
    'score' => 85
]);

AttemptAnswer::create([
    'quiz_attempt_id' => $attempt->id,
    'question_id' => 1,
    'selected_answer_id' => 2, // Float - correct
    'is_correct' => true
]);

AttemptAnswer::create([
    'quiz_attempt_id' => $attempt->id,
    'question_id' => 2,
    'selected_answer_id' => 4, // incorrect
    'is_correct' => false
]);
```

---

## 4. COMMANDES NÉCESSAIRES

```bash
# Compiler les assets
npm run dev      # Mode développement
npm run build    # Mode production

# Démarrer le serveur
php artisan serve

# Accéder à l'application
http://localhost:8000

# Comptes de test
Admin: admin@lms.test / password
Prof: prof1@lms.test / password
Étudiant: student1@lms.test / password
```

---

## 5. STRUCTURE DES ROUTES

### Routes Web (Authentifiées)

- GET /dashboard - Tableau de bord
- GET /courses - Liste des cours
- GET /courses/{id} - Détails du cours
- POST /courses/{id}/enroll - S'inscrire
- GET /courses/{id}/lessons/{lesson_id} - Voir une leçon
- GET /courses/{id}/lessons/{lesson_id}/quiz - Faire un quiz

### Routes Admin

- GET /admin/dashboard - Tableau de bord admin
- GET /admin/users - Gestion des utilisateurs
- GET /admin/courses - Gestion des cours
- GET /admin/reports - Rapports

### Routes Enseignant

- GET /teacher/courses - Mes cours
- POST /teacher/courses - Créer un cours
- GET /teacher/courses/{id}/edit - Éditer un cours
- POST /teacher/courses/{id}/lessons - Ajouter une leçon
- POST /teacher/courses/{id}/lessons/{lesson_id}/quiz - Créer un quiz
- GET /teacher/reports - Voir les résultats des étudiants

---

## 6. FONCTIONNALITÉS À IMPLÉMENTER

✅ Authentification et autorisation
✅ Gestion des cours (CRUD)
✅ Gestion des leçons
✅ Système de quiz avec questions/réponses
✅ Inscription aux cours
✅ Suivi de la progression
✅ Tableau de bord avec statistiques
⏳ Système de fichiers attachés
⏳ Notifications par email
⏳ Certificats de complétion
⏳ Système de commentaires

---

## 7. POUR ALLER PLUS LOIN

- Ajouter un système de paiement (Stripe)
- Ajouter des notifications email
- Ajouter des certificats PDF
- Système de commentaires et questions/réponses
- Système de rating/évaluation
- Export des résultats en PDF
- Système de chat en temps réel
- Intégration de visioconférence
