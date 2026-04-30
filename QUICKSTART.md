# 🚀 DÉMARRAGE RAPIDE - LMS ARSII

## ⚡ 5 MINUTES POUR LANCER L'APPLICATION

### Prérequis

- ✅ MySQL en cours d'exécution
- ✅ PHP 8.1+ installé
- ✅ Composer installé
- ✅ Node.js installé

---

## ÉTAPE 1️⃣ - Préparer l'environnement (2 minutes)

```powershell
cd c:\Users\ABDO\Desktop\laravel\lms-arsii

# Copier fichier de configuration
copy .env.example .env

# Générer clé APP
php artisan key:generate
```

✅ **Vérifier que .env contient:**

```env
DB_DATABASE=lms_arsii
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://localhost:8000
```

---

## ÉTAPE 2️⃣ - Préparer la base de données (1 minute)

```powershell
# Créer tables
php artisan migrate

# Peupler avec données test (6 users, 2 courses, etc.)
php artisan db:seed
```

✅ **Les données de seeding:**

- 6 utilisateurs (1 admin, 2 profs, 3 étudiants)
- 2 cours publiés
- 3 leçons
- 1 quiz avec 2 questions

---

## ÉTAPE 3️⃣ - Lancer les serveurs (2 minutes)

### Terminal 1 - Laravel Server

```powershell
cd c:\Users\ABDO\Desktop\laravel\lms-arsii
php artisan serve --host=localhost --port=8000
```

### Terminal 2 - Assets (Vite)

```powershell
cd c:\Users\ABDO\Desktop\laravel\lms-arsii
npm run dev
```

---

## ✅ C'EST PRÊT !

Ouvrir: **http://localhost:8000**

---

## 🔑 IDENTIFIANTS DE CONNEXION

### Admin

```
Email: admin@lms.test
Password: password123
```

### Professeur

```
Email: prof1@lms.test
Password: password123
```

### Étudiant

```
Email: student1@lms.test
Password: password123
```

---

## 🎯 WORKFLOWS RAPIDES À TESTER

### Tester comme Étudiant (3 minutes)

1. Login (student1@lms.test)
2. Cliquer "Courses"
3. S'inscrire au premier cours
4. Lire une leçon
5. Faire le quiz
6. Voir le score

### Tester comme Professeur (2 minutes)

1. Login (prof1@lms.test)
2. Voir son Dashboard (1 cours, 2 étudiants)
3. Cliquer /teacher/courses
4. Voir ses cours et résultats d'étudiants

### Tester comme Admin (1 minute)

1. Login (admin@lms.test)
2. Dashboard affiche stats (6 users, 2 courses)

---

## 📊 VÉRIFIER LE SYSTÈME

```powershell
php verify_system.php
```

Affichera ✅ ou les erreurs à corriger.

---

## 🔒 Sécurité & Authentification

- ✅ Login/Logout fonctionnel
- ✅ Password hashing (bcrypt)
- ✅ Session management (Laravel)
- ✅ CSRF protection
- ✅ Role-based access (middleware)

---

## 📝 DOCUMENTS UTILES

| Document                | Lire si...                               |
| ----------------------- | ---------------------------------------- |
| **README.md**           | Besoin de détails (architecture, routes) |
| **RESUME_FINAL.md**     | Besoin d'un résumé complet               |
| **VERSION_FINALE.md**   | Besoin de comprendre les corrections     |
| **FINAL_CHECKLIST.md**  | Pré-soutenance                           |
| **PFE_INSTRUCTIONS.md** | Exemples avancés (Tinker, scripts)       |

---

## ⚠️ TROUBLESHOOTING RAPIDE

### ❌ "Page Expired (419)"

```powershell
# Vérifier APP_URL dans .env (localhost ou 127.0.0.1)
php artisan config:cache
```

### ❌ "Connection Refused"

```powershell
# Vérifier MySQL en cours
# Dans .env: DB_HOST=127.0.0.1 (ou localhost)
```

### ❌ "Table doesn't exist"

```powershell
php artisan migrate:fresh --seed
```

### ❌ "Assets not loading (CSS/JS)"

```powershell
# S'assurer Terminal 2 est lancé
npm run dev
```

---

## ✨ C'EST FAIT !

Vous avez un **LMS complet et fonctionnel** prêt pour présentation !

**Bonne soutenance ! 🎓**
