# 📝 Guide d'Accès aux Quizzes pour les Étudiants

## 🎯 Comment Accéder aux Quizzes

### **Étape 1: Connexion**

1. Allez à `http://127.0.0.1:8000/login`
2. Connectez-vous avec un compte étudiant:
    - **Email:** `etudiant@lms.com`
    - **Mot de passe:** `Karim@2024`

### **Étape 2: Accès au Cours**

1. Cliquez sur **Dashboard Étudiant** depuis le menu
2. Ou allez à `http://127.0.0.1:8000/etudiant/dashboard`
3. Cliquez sur **"Mes Cours"** → vous verrez les cours auxquels vous êtes inscrit

### **Étape 3: Voir les Quizzes**

1. Cliquez sur un cours
2. **Vous verrez maintenant deux sections:**
    - ✅ **Leçons** (à gauche) - Avec tous les contenus pédagogiques
    - ✅ **Quiz** (à droite) - Avec les quizzes disponibles

### **Étape 4: Répondre au Quiz**

1. Cliquez sur le bouton **"Commencer"** (bouton vert avec ▶️)
2. **Vous verrez:**
    - ⏱ Le titre du quiz
    - ⏲️ Un chronomètre qui compte à rebours
    - ❓ Les questions (QCM, Vrai/Faux, ou Texte libre)
    - 📊 Les points pour chaque question

3. **Répondez à toutes les questions** et cliquez **"Soumettre"**

### **Étape 5: Voir Vos Résultats**

1. Après la soumission, allez à **"Mes Résultats"** depuis le menu
2. **Ou directement dans le cours**, un tableau montre:
    - Quiz suivi / Nombre de questions
    - Vos réponses / Score obtenu
    - Pourcentage de réussite

---

## 📊 Structure de la Vue Étudiant

### **Vue du Cours: `/etudiant/cours/{id}`**

```
┌─────────────────────────────────────────────────────────┐
│ Titre du Cours | Description                            │
├─────────────────────────────────────────────────────────┤
│ Progression Générale: ████████░░░░░░░░░░░░ 50%         │
├──────────────────────────┬──────────────────────────────┤
│ 📚 LEÇONS                │ ❓ QUIZ                       │
├──────────────────────────┼──────────────────────────────┤
│ • Installation (Leçon 1) │ • Quiz Laravel (⏱20min/10pts)│
│ • Eloquent ORM (L. 2)    │   [Commencer]                │
│ • Migrations (Leçon 3)   │                              │
│ • Authentification (L.4) │ • Quiz Eloquent (⏱25min/15pts)
│                          │   [Commencer]                │
├──────────────────────────┴──────────────────────────────┤
│ Vos Résultats sur ce Cours                             │
├────────────────┬──────────┬─────────┬───────┬──────────┤
│ Quiz           │ Questions│ Réponses│ Score │ Actions  │
├────────────────┼──────────┼─────────┼───────┼──────────┤
│ Laravel Fund.  │ 2        │ 2       │ 10/10 │ [Reprise]│
│ Eloquent ORM   │ 3        │ 0       │ --    │ [Démarrer]
└────────────────┴──────────┴─────────┴───────┴──────────┘
```

---

## 🎯 Flux Complet pour Répondre à un Quiz

```
1. Login
   ↓
2. Dashboard Étudiant
   ↓
3. "Mes Cours"
   ↓
4. Cliquer sur un Cours
   ↓
5. Voir les Quiz disponibles
   ↓
6. Cliquer "Commencer" sur un Quiz
   ↓
7. Voir l'interface de Quiz avec:
   - Titre et durée
   - Chronomètre qui compte à rebours
   - Questions à répondre
   ↓
8. Remplir les réponses (QCM, Vrai/Faux, Texte)
   ↓
9. Cliquer "Soumettre"
   ↓
10. Être redirigé vers "Mes Résultats"
   ↓
11. Voir le score obtenu et les détails
```

---

## ✅ Quizzes Disponibles

### **Cours 1: Laravel 11 - Backend Avancé**

#### **Quiz 1: Laravel Fondamentaux** (2 questions, 10 pts)

- **Question 1 (5 pts):** Quelle commande crée Laravel ?
    - Options: `composer create-project laravel/laravel`, `php artisan new`, `laravel new`
    - ✅ Réponse correcte: `composer create-project laravel/laravel`

- **Question 2 (5 pts):** Laravel utilise MVC
    - Options: `Vrai`, `Faux`
    - ✅ Réponse correcte: `Vrai`

#### **Quiz 2: Eloquent ORM** (3 questions, 15 pts)

- **Question 1 (5 pts):** Comment récupérer tous les utilisateurs avec Eloquent ?
    - Options: `User::all()`, `User::find()`, `User::get()`
    - ✅ Réponse correcte: `User::all()`

- **Question 2 (5 pts):** Les migrations permettent de versionner le schéma
    - Options: `Vrai`, `Faux`
    - ✅ Réponse correcte: `Vrai`

- **Question 3 (5 pts):** Quelle est la commande pour créer une migration ?
    - Options: `php artisan make:migration`, `php artisan migration:create`, `php artisan create:migration`
    - ✅ Réponse correcte: `php artisan make:migration`

### **Cours 2: HTML5 et CSS3**

#### **Quiz 1: HTML5 Fondamentaux** (3 questions, 12 pts)

- **Question 1 (4 pts):** Quel élément crée un paragraphe ?
    - ✅ Réponse correcte: `<p>`

- **Question 2 (4 pts):** HTML5 est une évolution de HTML4
    - ✅ Réponse correcte: `Vrai`

- **Question 3 (4 pts):** Quel est l'attribut pour un lien hypertexte ?
    - ✅ Réponse correcte: `href`

#### **Quiz 2: CSS3 Avancé** (3 questions, 15 pts)

- **Question 1 (5 pts):** Flexbox est pour les layouts 1D
    - ✅ Réponse correcte: `Vrai`

- **Question 2 (5 pts):** CSS Grid est pour les layouts 2D
    - ✅ Réponse correcte: `Vrai`

- **Question 3 (5 pts):** Quelle propriété centre un élément avec Flexbox ?
    - ✅ Réponse correcte: `justify-content et align-items`

---

## 🔒 Restrictions & Autorisations

| Action             | Étudiant       | Enseignant         | Admin     |
| ------------------ | -------------- | ------------------ | --------- |
| **Voir Quiz**      | ✅ (ses cours) | ✅ (tous)          | ✅ (tous) |
| **Créer Quiz**     | ❌             | ✅ (ses cours)     | ✅        |
| **Modifier Quiz**  | ❌             | ✅ (ses quiz)      | ✅        |
| **Supprimer Quiz** | ❌             | ✅ (ses quiz)      | ✅        |
| **Répondre Quiz**  | ✅ (illimité)  | ❌                 | ❌        |
| **Voir Résultats** | ✅ (siens)     | ✅ (ses étudiants) | ✅ (tous) |

---

## 🐛 Troubleshooting

### **Les Quiz ne s'affichent pas?**

- ✅ **Solution:** Vérifiez que vous êtes inscrit au cours
- Allez dans "Catalogue" et inscrivez-vous si nécessaire

### **Le chronomètre ne fonctionne pas?**

- ✅ Pas grave! Le quiz se soumet automatiquement après le délai
- Vous pouvez aussi soumettre manuellement avant

### **Je ne vois pas mes résultats?**

- ✅ Allez à "Mes Résultats" depuis le menu principal
- Ou voir dans le tableau "Vos Résultats sur ce Cours"

### **Les points ne s'ajoutent pas correctement?**

- ✅ Les points sont calculés automatiquement selon:
    - 1 point par question correcte
    - 0 point par question incorrecte
    - Le total est la somme de tous les scores

---

## 📞 Support

Pour toute question ou problème:

1. Vérifiez que vous êtes connecté avec un compte étudiant
2. Vérifiez que le cours a des quiz (voir "Quizzes Disponibles" ci-dessus)
3. Assurez-vous d'être inscrit au cours
4. Contactez votre administrateur si le problème persiste
