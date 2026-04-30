<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Cours;
use App\Models\Lecon;
use App\Models\Quiz;
use App\Models\Question;
use App\Policies\CoursPolicy;
use App\Policies\LeconPolicy;
use App\Policies\QuizPolicy;
use App\Policies\QuestionPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Cours::class => CoursPolicy::class,
        Lecon::class => LeconPolicy::class,
        Quiz::class => QuizPolicy::class,
        Question::class => QuestionPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
