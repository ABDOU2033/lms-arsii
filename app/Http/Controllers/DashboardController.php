<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\User;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->isTeacher()) {
            return $this->teacherDashboard($user);
        } elseif ($user->isStudent()) {
            return $this->studentDashboard($user);
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard($user);
        }
        
        return redirect('/');
    }

    private function teacherDashboard($user)
    {
        $courses = Course::where('teacher_id', $user->id)->count();
        $students = $user->teacherCourses()
            ->withCount('students')
            ->get()
            ->sum('students_count');
        $quizzes = Quiz::whereHas('lesson.course', function($q) use ($user) {
            $q->where('teacher_id', $user->id);
        })->count();
        
        $recentCourses = Course::where('teacher_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.teacher', compact(
            'courses',
            'students',
            'quizzes',
            'recentCourses'
        ));
    }

    private function studentDashboard($user)
    {
        $enrolledCourses = $user->studentCourses()->count();
        $completedCourses = $user->studentCourses()
            ->wherePivot('progress', 100)
            ->count();
        $averageProgress = $user->studentCourses()
            ->avg('progress') ?? 0;
        
        $recentCourses = $user->studentCourses()
            ->with('teacher')
            ->latest('course_student.updated_at')
            ->take(5)
            ->get();

        // Statistiques des quiz
        $quizAttempts = QuizAttempt::where('student_id', $user->id)
            ->with('quiz')
            ->latest()
            ->take(5)
            ->get();

        $averageQuizScore = QuizAttempt::where('student_id', $user->id)
            ->whereNotNull('score')
            ->avg('score') ?? 0;

        return view('dashboard.student', compact(
            'enrolledCourses',
            'completedCourses',
            'averageProgress',
            'recentCourses',
            'quizAttempts',
            'averageQuizScore'
        ));
    }

    private function adminDashboard($user)
    {
        $totalUsers = User::count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalCourses = Course::count();
        
        $recentUsers = User::latest()->take(10)->get();
        $recentCourses = Course::latest()->take(5)->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalTeachers',
            'totalStudents',
            'totalCourses',
            'recentUsers',
            'recentCourses'
        ));
    }
}
