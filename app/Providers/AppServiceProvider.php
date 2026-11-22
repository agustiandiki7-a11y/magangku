<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tampilan pagination sederhana (di-style lewat public/assets/css/app.css).
        Paginator::defaultView('pagination::default');
        Paginator::defaultSimpleView('pagination::simple-default');

        // Tanggal berbahasa Indonesia (mis. "3 hari yang lalu", "Senin").
        Carbon::setLocale('id');
    }
}
