<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ========== AFFICHAGE POUR LES ÉTUDIANTS ==========
    public function index()
    {
        $courses = Course::with('teacher')->latest()->paginate(12);
        $enrolledIds = auth()->user()->studentCourses()->pluck('course_id')->toArray();
        return view('courses.index', compact('courses', 'enrolledIds'));
    }

    public function show(Course $course)
    {
        $course->load(['teacher', 'lessons.quiz']);
        return view('courses.show', compact('course'));
    }

    // ========== CRUD POUR LES ENSEIGNANTS ==========
    public function create()
    {
        $this->authorize('create', Course::class);
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Course::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|image|max:2048',
            'level' => 'required|in:beginner,intermediate,advanced',
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'level.required' => 'Le niveau est obligatoire.'
        ]);
        
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('courses', 'public');
            $validated['thumbnail'] = $path;
        }
        
        $validated['teacher_id'] = auth()->id();
        $validated['is_published'] = $request->boolean('is_published', false);
        
        Course::create($validated);
        
        return redirect()->route('teacher.courses')->with('success', 'Cours créé avec succès!');
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|image|max:2048',
            'level' => 'required|in:beginner,intermediate,advanced',
        ]);
        
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $path = $request->file('thumbnail')->store('courses', 'public');
            $validated['thumbnail'] = $path;
        }
        
        $validated['is_published'] = $request->boolean('is_published', false);
        $course->update($validated);
        
        return redirect()->route('teacher.courses.edit', $course)->with('success', 'Cours mis à jour avec succès!');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        
        $course->delete();
        
        return redirect()->route('teacher.courses')->with('success', 'Cours supprimé avec succès!');
    }

    // ========== INSCRIPTION/DÉSINSCRIPTION ==========
    public function enroll(Request $request, Course $course)
    {
        $user = auth()->user();
        
        if (!$user->isStudent()) {
            return back()->with('error', 'Seuls les étudiants peuvent s\'inscrire aux cours.');
        }
        
        if ($user->studentCourses()->where('course_id', $course->id)->exists()) {
            return back()->with('info', 'Vous êtes déjà inscrit à ce cours.');
        }
        
        $user->studentCourses()->attach($course->id, [
            'progress' => 0,
            'enrolled_at' => now()
        ]);
        
        return back()->with('success', 'Inscription réussie!');
    }

    public function unenroll(Request $request, Course $course)
    {
        $user = auth()->user();
        $user->studentCourses()->detach($course->id);
        
        return redirect()->route('courses.index')->with('success', 'Désinscription réussie.');
    }

    public function myCourses()
    {
        $user = auth()->user();
        
        if ($user->isTeacher()) {
            $courses = $user->teacherCourses()->latest()->paginate(10);
            return view('courses.my-teacher', compact('courses'));
        } elseif ($user->isStudent()) {
            $courses = $user->studentCourses()->with('teacher')->latest('course_student.updated_at')->paginate(10);
            return view('courses.my-student', compact('courses'));
        }
        
        abort(403, 'Accès non autorisé.');
    }
}
