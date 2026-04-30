# BASE DE DONNÉES - SCHÉMA LMS

## Vue d'ensemble des tables

```
users
├── id
├── name
├── email
├── password
├── email_verified_at
├── role (admin, teacher, student)
├── avatar
├── phone
├── specialization
├── bio
└── timestamps

courses
├── id
├── title
├── slug
├── description
├── teacher_id (FK -> users)
├── category
├── thumbnail
├── level (beginner, intermediate, advanced)
├── price
├── is_published
└── timestamps

lessons
├── id
├── course_id (FK -> courses)
├── title
├── description
├── content
├── order
├── is_free
├── video_url
├── attachments
└── timestamps

quizzes
├── id
├── lesson_id (FK -> lessons)
├── title
├── description
├── passing_score
├── time_limit
└── timestamps

questions
├── id
├── quiz_id (FK -> quizzes)
├── question_text
├── question_type (multiple_choice, short_answer, essay)
├── order
└── timestamps

answers
├── id
├── question_id (FK -> questions)
├── answer_text
├── is_correct
├── order
└── timestamps

quiz_attempts
├── id
├── quiz_id (FK -> quizzes)
├── student_id (FK -> users)
├── started_at
├── finished_at
├── score
└── timestamps

attempt_answers
├── id
├── quiz_attempt_id (FK -> quiz_attempts)
├── question_id (FK -> questions)
├── selected_answer_id (FK -> answers)
├── is_correct
└── timestamps

course_student (table pivot)
├── id
├── course_id (FK -> courses)
├── student_id (FK -> users)
├── progress (0-100)
├── enrolled_at
└── timestamps
```

## Requêtes SQL utiles

### Inscrire un étudiant à un cours

```sql
INSERT INTO course_student (course_id, student_id, progress, enrolled_at)
VALUES (1, 5, 0, NOW());
```

### Voir tous les étudiants d'un cours

```sql
SELECT u.* FROM users u
JOIN course_student cs ON u.id = cs.student_id
WHERE cs.course_id = 1;
```

### Voir les résultats des quiz d'un étudiant

```sql
SELECT q.title, qa.score, qa.finished_at FROM quiz_attempts qa
JOIN quizzes q ON qa.quiz_id = q.id
WHERE qa.student_id = 5
ORDER BY qa.finished_at DESC;
```

### Voir la progression d'un étudiant dans un cours

```sql
SELECT u.name, cs.progress FROM course_student cs
JOIN users u ON cs.student_id = u.id
WHERE cs.course_id = 1;
```

### Obtenir les statistiques d'un quiz

```sql
SELECT
    AVG(score) as average_score,
    MIN(score) as min_score,
    MAX(score) as max_score,
    COUNT(*) as total_attempts
FROM quiz_attempts
WHERE quiz_id = 1;
```

### Voir les cours créés par un enseignant

```sql
SELECT * FROM courses WHERE teacher_id = 2;
```

### Voir le nombre d'étudiants par cours

```sql
SELECT c.title, COUNT(cs.student_id) as student_count
FROM courses c
LEFT JOIN course_student cs ON c.id = cs.course_id
GROUP BY c.id;
```
