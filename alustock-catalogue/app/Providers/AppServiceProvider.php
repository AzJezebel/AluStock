<?php

namespace App\Providers;

use App\Http\ViewComposers\SidebarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Nom réel de la vue (l'ancien 'partials.sidebar-menu' ne correspondait à rien).
        View::composer('public.partials.sidebar-menu', SidebarComposer::class);
    }
}
