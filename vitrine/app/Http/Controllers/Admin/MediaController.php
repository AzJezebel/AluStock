<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query();

        if ($request->filled('search')) {
            $query->where('file_name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('mime_type', 'LIKE', $request->type . '%');
        }

        $medias = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.medias.index', compact('medias'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'collection' => 'required|string'
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads/' . $request->collection, 'public');

        $media = Media::create([
            'model_type' => $request->model_type,
            'model_id' => $request->model_id,
            'collection_name' => $request->collection,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'is_primary' => $request->boolean('is_primary', false)
        ]);

        return response()->json([
            'success' => true,
            'media' => $media,
            'url' => $media->url
        ]);
    }

    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return redirect()->back()->with('success', 'Média supprimé avec succès.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        foreach ($ids as $id) {
            $media = Media::find($id);
            if ($media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            }
        }

        return response()->json(['success' => true]);
    }

    public function setPrimary(Media $media)
    {
        // Désactiver les autres médias primaires du même modèle
        Media::where('model_type', $media->model_type)
            ->where('model_id', $media->model_id)
            ->where('collection_name', $media->collection_name)
            ->update(['is_primary' => false]);

        $media->update(['is_primary' => true]);

        return response()->json(['success' => true]);
    }
}