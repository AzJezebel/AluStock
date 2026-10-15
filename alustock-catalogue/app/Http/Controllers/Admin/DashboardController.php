<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Composant;
use App\Models\Categorie;
use App\Models\TypeComposant;
use App\Models\Media;
use App\Models\Document;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            // Contenu principal
            'ouvrages' => Ouvrage::count(),
            'composants' => Composant::count(),
            'medias' => Media::count(),
            'documents' => Document::count(),

            // Classification
            'categories' => Categorie::count(),
            'types_composant' => TypeComposant::count(),
        ];

        $derniersOuvrages = Ouvrage::with('categorie')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'derniersOuvrages'));
    }
}