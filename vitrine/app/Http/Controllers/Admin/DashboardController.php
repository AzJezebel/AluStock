<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Categorie;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_ouvrages' => Ouvrage::count(),
            'active_ouvrages' => Ouvrage::actif()->count(),
            'total_categories' => Categorie::count(),
            'total_users' => User::count(),
            'featured_ouvrages' => Ouvrage::featured()->count(),
            'total_views' => Ouvrage::sum('views')
        ];

        // Derniers ouvrages ajoutés (liste du dashboard)
        $latestOuvrages = Ouvrage::with('categorie')->recent(5)->get();

        $chartData = $this->getChartData();

        return view('admin.dashboard.index', compact('stats', 'latestOuvrages', 'chartData'));
    }

    private function getChartData()
    {
        $ouvragesParMois = Ouvrage::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return [
            'labels' => $ouvragesParMois->map(function ($item) {
                return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
            })->reverse()->values(),
            'values' => $ouvragesParMois->pluck('count')->reverse()->values()
        ];
    }
}