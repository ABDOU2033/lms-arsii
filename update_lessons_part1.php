<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Lesson 71: Introduction aux Algorithmes
DB::table('lecons')->where('id', 71)->update([
    'contenu' => "# Introduction aux Algorithmes\n\n## 1. Qu'est-ce qu'un Algorithme ?\n\nUn **algorithme** est une suite finie et ordonnée d'instructions permettant de résoudre un problème.\n\n### Caractéristiques :\n- **Précis** : Chaque étape est clairement définie\n- **Déterministe** : Même entrée = Même sortie\n- **Fini** : Se termine après un nombre fini d'étapes\n\n## 2. Exemple Simple : Trouver le Maximum\n\n```\nAlgorithme Maximum\nVariables a, b, max : Entier\nDébut\n    Lire(a)\n    Lire(b)\n    Si a > b Alors\n        max ← a\n    Sinon\n        max ← b\n    FinSi\n    Écrire(\"Maximum : \", max)\nFin\n```\n\n## 3. Complexité Algorithmique\n\n**Notation Big O :**\n- **O(1)** : Temps constant\n- **O(log n)** : Logarithmique\n- **O(n)** : Linéaire\n- **O(n log n)** : Quasi-linéaire\n- **O(n²)** : Quadratique\n\n## 4. Types d'Algorithmes\n\n### Algorithmes de Tri\n- Tri à bulles : O(n²)\n- Tri rapide (Quick Sort) : O(n log n)\n- Tri fusion (Merge Sort) : O(n log n)\n\n### Algorithmes de Recherche\n- Recherche linéaire : O(n)\n- Recherche dichotomique : O(log n)\n\n## Points Clés\n\n📌 Un algorithme est une méthode étape par étape\n📌 La complexité mesure l'efficacité\n📌 Choisissez toujours l'algorithme le plus efficace"
]);

echo "✅ Leçon 71 mise à jour\n";
