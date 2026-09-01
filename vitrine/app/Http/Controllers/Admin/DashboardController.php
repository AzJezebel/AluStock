<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Categorie;
use App\Models\Gamme;
use App\Models\Media;
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
            'total_gammes' => Gamme::count(),
            'total_medias' => Media::count(),
            'total_users' => User::count(),
            'latest_ouvrages' => Ouvrage::recent(5)->get(),
            'featured_ouvrages' => Ouvrage::featured()->count(),
            'total_views' => Ouvrage::sum('views')
        ];

        $chartData = $this->getChartData();

        return view('admin.dashboard.index', compact('stats', 'chartData'));
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