# ✅ CHECKLIST DE VÉRIFICATION - LMS ARSII

Utilisez cette liste pour vérifier que TOUS les éléments du système fonctionnent correctement.

## 🔧 VÉRIFICATIONS TECHNIQUES

### Environnement

- [ ] PHP 8.2+ installé: `php --version`
- [ ] MySQL en cours d'exécution
- [ ] Composer installé: `composer --version`
- [ ] Node.js/npm installé: `node --version` et `npm --version`
- [ ] Git configuré: `git config --list`

### Installation

- [ ] `composer install` exécuté sans erreurs
- [ ] `npm install` exécuté sans erreurs
- [ ] `.env` créé et configuré
- [ ] `php artisan key:generate` exécuté
- [ ] Base de données `lms_arsii` créée

### Migrations

- [ ] `php artisan migrate` exécuté avec succès
- [ ] Vérifier: `php artisan migrate:status` (tous les Ran)
- [ ] Tables visibles dans MySQL
- [ ] Pivot table `course_student` créée
- [ ] Colonnes correctes dans les tables

### Serveurs

- [ ] `php artisan serve --host=127.0.0.1 --port=8000` lancé
- [ ] Site accessible: http://127.0.0.1:8000
- [ ] `npm run dev` compilant sans erreurs
- [ ] Assets chargés (CSS, JS)

---

## 🎯 TESTS FONCTIONNELS

### Test 1: Authentification

**Objectif**: Vérifier que la connexion/déconnexion fonctionne

1. [ ] Aller à http://127.0.0.1:8000
2. [ ] Cliquer "Connexion"
3. [ ] Entrer: `student1@lms.test` / `password123`
4. [ ] Bouton "Se connecter" fonctionne
5. [ ] Redirection vers dashboard
6. [ ] Voir le nom de l'utilisateur dans la navigation
7. [ ] Cliquer sur profil → menu déroulant
8. [ ] Cliquer "Déconnexion"
9. [ ] Redirection vers page d'accueil

### Test 2: Parcourir les Cours (Étudiant)

**Objectif**: Vérifier que les cours sont affichés correctement

1. [ ] Aller à /courses
2. [ ] Voir une liste de cours en grille
3. [ ] Chaque cours a: image, titre, description, enseignant
4. [ ] Bouton "Détails" cliquable
5. [ ] Bouton "S'inscrire" cliquable
6. [ ] Niveau du cours affiché (Débutant/Intermédiaire/Avancé)
7. [ ] Pagination fonctionne (si > 12 cours)

### Test 3: Créer un Cours (Enseignant)

**Objectif**: Vérifier que les enseignants peuvent créer des cours

1. [ ] Déconnecter l'étudiant
2. [ ] Connecter `prof1@lms.test` / `password123`
3. [ ] Voir "Mes Cours" dans la navigation
4. [ ] Cliquer "Mes Cours"
5. [ ] Voir "+ Nouveau Cours"
6. [ ] Cliquer créer un cours
7. [ ] Remplir: titre, description, catégorie, niveau
8. [ ] Uploader une image
9. [ ] Cliquer "Créer le Cours"
10. [ ] Message "Cours créé avec succès!"
11. [ ] Redirection vers édition du cours

### Test 4: Ajouter une Leçon

**Objectif**: Vérifier que les leçons peuvent être créées

1. [ ] Sur la page d'édition du cours créé
2. [ ] Voir le formulaire du cours
3. [ ] Voir la sidebar "Leçons" à droite
4. [ ] Cliquer "+ Ajouter"
5. [ ] Formulaire créer leçon s'ouvre
6. [ ] Remplir: titre, contenu, ordre
7. [ ] Cliquer "Créer la Leçon"
8. [ ] Message "Leçon créée avec succès!"
9. [ ] Leçon apparaît dans la sidebar
10. [ ] Créer 2-3 leçons

### Test 5: Éditer une Leçon

**Objectif**: Vérifier que les leçons peuvent être modifiées

1. [ ] Dans sidebar des leçons, cliquer "Modifier"
2. [ ] Formulaire d'édition s'ouvre
3. [ ] Modifier le titre
4. [ ] Cliquer "Mettre à Jour"
5. [ ] Message de succès
6. [ ] Titre mis à jour dans sidebar

### Test 6: Publier le Cours

**Objectif**: Vérifier que le cours peut être publié

1. [ ] Sur la page d'édition
2. [ ] Cocher "Publier ce cours"
3. [ ] Cliquer "Mettre à Jour"
4. [ ] Le cours est maintenant public

### Test 7: S'Inscrire (Étudiant)

**Objectif**: Vérifier que les étudiants peuvent s'inscrire

1. [ ] Déconnecter le professeur
2. [ ] Connecter `student1@lms.test` / `password123`
3. [ ] Aller à /courses
4. [ ] Voir le cours créé
5. [ ] Cliquer "S'inscrire"
6. [ ] Message "Inscription réussie!"
7. [ ] Bouton change en "Quitter"

### Test 8: Consulter les Leçons (Étudiant)

**Objectif**: Vérifier que les étudiants voient les leçons

1. [ ] Aller à "Mes Cours"
2. [ ] Cliquer "Continuer" sur le cours
3. [ ] Voir les détails du cours
4. [ ] Voir la liste des leçons
5. [ ] Cliquer sur une leçon
6. [ ] Contenu s'affiche
7. [ ] Cliquer sur une autre leçon
8. [ ] Contenu s'affiche correctement

### Test 9: Quitter un Cours

**Objectif**: Vérifier que les étudiants peuvent quitter

1. [ ] Dans "Mes Cours", cliquer "Quitter"
2. [ ] Popup de confirmation s'affiche
3. [ ] Cliquer "OK"
4. [ ] Message "Désinscription réussie"
5. [ ] Cours disparaît de "Mes Cours"

### Test 10: Supprimer une Leçon

**Objectif**: Vérifier que les leçons peuvent être supprimées

1. [ ] Connecter le professeur
2. [ ] Aller à "Mes Cours" > Modifier un cours
3. [ ] Dans sidebar, cliquer "Supp." sur une leçon
4. [ ] Popup de confirmation
5. [ ] Leçon disparaît après confirmation

### Test 11: Supprimer un Cours

**Objectif**: Vérifier que les cours peuvent être supprimés

1. [ ] Aller à "Mes Cours"
2. [ ] Sur un cours, cliquer "Supprimer"
3. [ ] Popup de confirmation
4. [ ] Cours disparaît après confirmation
5. [ ] Message de succès

### Test 12: Sécurité (Autorisation)

**Objectif**: Vérifier que la sécurité fonctionne

1. [ ] Connecter `student1@lms.test`
2. [ ] Essayer d'accéder à `/teacher/courses/create`
3. [ ] Erreur 403 affichée
4. [ ] Essayer de modifier un cours d'un autre prof (URL change)
5. [ ] Erreur 403 affichée
6. [ ] Les étudiants ne voient pas les boutons "Modifier/Supprimer"

### Test 13: Validation des Formulaires

**Objectif**: Vérifier que la validation fonctionne

1. [ ] Créer un cours
2. [ ] Laisser le titre vide et soumettre
3. [ ] Message d'erreur s'affiche
4. [ ] Même pour description, niveau
5. [ ] Les erreurs disparaissent quand on remplit correctement

### Test 14: Messages Flash

**Objectif**: Vérifier que les messages de succès/erreur s'affichent

1. [ ] Après chaque action (créer, modifier, supprimer)
2. [ ] Message de succès vert s'affiche
3. [ ] Message disparaît après quelques secondes
4. [ ] Les erreurs s'affichent en rouge

### Test 15: Design Responsive

**Objectif**: Vérifier que le design répond sur tous les appareils

1. [ ] Ouvrir DevTools (F12)
2. [ ] Activer responsive design
3. [ ] Tester sur mobile (375px)
4. [ ] Navigation burger menu
5. [ ] Grid se empile correctement
6. [ ] Boutons restent cliquables
7. [ ] Images responsive
8. [ ] Texte lisible

---

## 🗂️ VÉRIFICATIONS DE FICHIERS

### Modèles

- [ ] `app/Models/User.php` - Relations définies
- [ ] `app/Models/Course.php` - Relations définies
- [ ] `app/Models/Lesson.php` - Relations définies
- [ ] `app/Models/Quiz.php` - Existe
- [ ] `app/Models/Question.php` - Existe
- [ ] `app/Models/Answer.php` - Existe

### Contrôleurs

- [ ] `app/Http/Controllers/CourseController.php` - 9 méthodes
- [ ] `app/Http/Controllers/LessonController.php` - 6 méthodes
- [ ] `app/Http/Controllers/DashboardController.php` - Existe

### Middlewares

- [ ] `app/Http/Middleware/TeacherMiddleware.php` - Créé
- [ ] `app/Http/Middleware/AdminMiddleware.php` - Créé
- [ ] Enregistrés dans `bootstrap/app.php`

### Vues (Blade)

- [ ] `resources/views/courses/index.blade.php`
- [ ] `resources/views/courses/show.blade.php`
- [ ] `resources/views/courses/create.blade.php`
- [ ] `resources/views/courses/edit.blade.php`
- [ ] `resources/views/courses/my-teacher.blade.php`
- [ ] `resources/views/courses/my-student.blade.php`
- [ ] `resources/views/lessons/show.blade.php`
- [ ] `resources/views/lessons/create.blade.php`
- [ ] `resources/views/lessons/edit.blade.php`
- [ ] `resources/views/layouts/navigation.blade.php`

### Routes

- [ ] `routes/web.php` - Routes authentifiées
- [ ] Routes par rôle (teacher, admin)
- [ ] Routes RESTful complètes

### Base de Données

- [ ] Table `users`
- [ ] Table `courses`
- [ ] Table `lessons`
- [ ] Table `course_student` (pivot)
- [ ] Table `quizzes`
- [ ] Table `questions`
- [ ] Table `answers`

---

## 📊 RÉSUMÉ DU TEST

Après avoir coché tous les éléments ci-dessus:

- **Total**: **\_** / **\_** cases cochées
- **Statut**:
    - [ ] ✅ **100% FONCTIONNEL** (Tout vérifié)
    - [ ] ⚠️ **Problèmes mineurs** (Certains fonctionnent)
    - [ ] ❌ **Problèmes majeurs** (Beaucoup ne fonctionnent pas)

### Notes/Remarques:

```
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```

---

**Date du test**: ******\_******
**Testeur**: ********\_********
**Version testé**: 1.0.0

✅ **Si tout est coché, le système est 100% FONCTIONNEL et PRÊT POUR LA PRODUCTION**
