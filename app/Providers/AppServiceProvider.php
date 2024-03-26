<?php

namespace App\Providers;

use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        // View::composer(['doc.pengumuman.pengelola.index'], function ($view) {
        //     $semesters = Semester::getSimpleSemester();
        //     $view->with('semesters', $semesters);
        // });
    }
}
