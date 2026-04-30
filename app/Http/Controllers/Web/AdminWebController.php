<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cours;
use App\Models\Quiz;
use App\Models\Reponse;
use Illuminate\Http\Request;

class AdminWebController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalCours = Cours::count();
        $totalQuiz = Quiz::count();
        $totalResponses = Reponse::count();
        $dernierUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsers', 'totalCours', 'totalQuiz', 'totalResponses', 'dernierUsers'));
    }

    public function utilisateurs(Request $request)
    {
        $query = User::query();

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->search) {
            $query->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $utilisateurs = $query->paginate(10);

        return view('admin.utilisateurs', compact('utilisateurs'));
    }

    public function createUtilisateur()
    {
        return view('admin.utilisateurs_create');
    }

    public function storeUtilisateur(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:etudiant,enseignant,administrateur',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'mot_de_passe' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'actif' => true,
        ]);

        // Créer l'entité associée selon le rôle
        if ($user->role === 'etudiant') {
            \App\Models\Etudiant::create(['user_id' => $user->id, 'niveau' => 'Non spécifié']);
        } elseif ($user->role === 'enseignant') {
            \App\Models\Enseignant::create(['user_id' => $user->id, 'specialite' => 'Non spécifié', 'bio' => null]);
        }

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur ajouté.');
    }

    public function editUtilisateur(User $user)
    {
        return view('admin.utilisateurs_edit', compact('user'));
    }

    public function updateUtilisateur(Request $request, User $user)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:etudiant,enseignant,administrateur',
        ]);

        $data = $request->only(['nom', 'email', 'role']);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['mot_de_passe'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $oldRole = $user->role;
        $user->update($data);

        if ($oldRole !== $request->role) {
            if ($oldRole === 'etudiant') \App\Models\Etudiant::where('user_id', $user->id)->delete();
            if ($oldRole === 'enseignant') \App\Models\Enseignant::where('user_id', $user->id)->delete();

            if ($request->role === 'etudiant') \App\Models\Etudiant::create(['user_id' => $user->id, 'niveau' => 'Non spécifié']);
            if ($request->role === 'enseignant') \App\Models\Enseignant::create(['user_id' => $user->id, 'specialite' => 'Non spécifié', 'bio' => null]);
        }

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur modifié.');
    }

    public function activerUtilisateur(User $user)
    {
        $user->update(['actif' => true]);
        return redirect()->back()->with('success', 'Utilisateur activé.');
    }

    public function desactiverUtilisateur(User $user)
    {
        if ($user->role !== 'administrateur') {
            $user->update(['actif' => false]);
        }
        return redirect()->back()->with('success', 'Utilisateur désactivé.');
    }

    public function supprimerUtilisateur(User $user)
    {
        if ($user->role !== 'administrateur') {
            $user->delete();
        }
        return redirect()->back()->with('success', 'Utilisateur supprimé.');
    }

    public function statistiques()
    {
        $stats = [
            'total_users' => User::count(),
            'etudiants' => User::where('role', 'etudiant')->count(),
            'enseignants' => User::where('role', 'enseignant')->count(),
            'administrateurs' => User::where('role', 'administrateur')->count(),
            'active_users' => User::where('actif', true)->count(),
            'total_cours' => Cours::count(),
            'published_cours' => Cours::where('statut', 'publie')->count(),
            'total_quiz' => Quiz::count(),
            'total_responses' => Reponse::count(),
        ];

        // répartition des inscriptions par mois (nombre d'étudiants inscrits aux cours)
        $inscriptionsParMois = \App\Models\Inscription::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        $stats['inscriptions_par_mois'] = array_map(function ($month) use ($inscriptionsParMois) {
            return $inscriptionsParMois[$month] ?? 0;
        }, range(1, 12));

        return view('admin.statistiques', compact('stats'));
    }

    public function reponses(Request $request)
    {
        $query = Reponse::with(['question.quiz', 'etudiant']);

        if ($request->search) {
            $query->whereHas('etudiant', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhereHas('question.quiz', function ($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%');
            });
        }

        $reponses = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.reponses', compact('reponses'));
    }
}