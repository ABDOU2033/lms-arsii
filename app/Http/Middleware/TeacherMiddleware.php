<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (!auth()->user()->isTeacher() && !auth()->user()->isAdmin()) {
            abort(403, 'Accès réservé aux enseignants.');
        }

        return $next($request);
    }
}
