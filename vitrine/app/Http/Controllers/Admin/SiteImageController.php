<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\SiteImages;
use Illuminate\Http\Request;

/**
 * Gestion des images du site (hero + photo « À propos ») — sans base de données.
 * Voir App\Support\SiteImages pour le stockage (fichier JSON).
 */
class SiteImageController extends Controller
{
    public function index()
    {
        return view('admin.site-images.index');
    }

    public function store(Request $request, string $slot)
    {
        $this->assertSlot($slot);

        $request->validate([
            'fichiers'   => 'required|array|max:10',
            'fichiers.*' => 'image|mimes:jpg,jpeg,png,webp|max:8192',
        ], [
            'fichiers.required' => 'Choisissez au moins une image.',
            'fichiers.*.image'  => 'Chaque fichier doit être une image.',
            'fichiers.*.mimes'  => 'Formats acceptés : JPG, PNG, WebP.',
            'fichiers.*.max'    => 'Chaque image doit faire 8 Mo au maximum.',
        ]);

        $files = $request->file('fichiers');

        // Emplacement « une seule image » : on garde la première et on remplace l'ancienne.
        if (! SiteImages::SLOTS[$slot]['multiple']) {
            $files = [reset($files)];
        }

        foreach ($files as $file) {
            SiteImages::add($slot, $file);
        }

        return back()->with('success', count($files) > 1
            ? count($files) . ' images ajoutées.'
            : 'Image enregistrée.');
    }

    public function destroy(Request $request, string $slot)
    {
        $this->assertSlot($slot);
        $request->validate(['path' => 'required|string']);

        SiteImages::remove($slot, $request->input('path'));

        return back()->with('success', 'Image supprimée.');
    }

    public function reorder(Request $request, string $slot)
    {
        $this->assertSlot($slot);
        $request->validate(['order' => 'required|array', 'order.*' => 'string']);

        SiteImages::reorder($slot, $request->input('order'));

        return response()->json(['ok' => true]);
    }

    private function assertSlot(string $slot): void
    {
        abort_unless(isset(SiteImages::SLOTS[$slot]), 404);
    }
}