<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Ouvrage;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_ouvrages'    => Ouvrage::count(),
            'active_ouvrages'   => Ouvrage::actif()->count(),
            'featured_ouvrages' => Ouvrage::featured()->count(),
            'total_categories'  => Categorie::count(),
            'total_views'       => (int) Ouvrage::sum('views'),
        ];

        $latestOuvrages = Ouvrage::with('categorie')->recent(5)->get();

        return view('admin.dashboard.index', compact('stats', 'latestOuvrages'));
    }
}