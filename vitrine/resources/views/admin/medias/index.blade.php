{{-- resources/views/admin/medias/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Gestion des médias - AluStock Admin')
@section('page-title', 'Gestion des médias')
@section('page-subtitle', 'Gérez toutes les images de vos ouvrages')

@section('content')
<div class="space-y-6">
    <!-- Barre d'outils -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Recherche -->
                <form action="{{ route('admin.medias.index') }}" method="GET" class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher un média..."
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent w-64">
                    <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <!-- Filtre par type -->
                <select name="type" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="">Tous les types</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Vidéos</option>
                    <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documents</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Grille des médias -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @forelse($medias as $media)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden group">
            <div class="relative">
                @if(str_starts_with($media->mime_type, 'image/'))
                    <img src="{{ asset('storage/' . $media->file_path) }}" 
                         alt="{{ $media->file_name }}" 
                         class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-file text-4xl text-gray-400"></i>
                    </div>
                @endif
                
                <!-- Badge collection -->
                <span class="absolute top-2 left-2 px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                    {{ $media->collection_name }}
                </span>
                
                <!-- Badge primary -->
                @if($media->is_primary)
                <span class="absolute top-2 right-2 px-2 py-1 bg-amber-500 text-white text-xs rounded-full">
                    <i class="fas fa-star"></i> Principal
                </span>
                @endif
            </div>
            
            <div class="p-3">
                <p class="text-xs text-gray-600 truncate" title="{{ $media->file_name }}">
                    {{ $media->file_name }}
                </p>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs text-gray-400">
                        {{ $media->size }}
                    </span>
                    <div class="flex items-center space-x-1">
                        @if($media->is_primary)
                            <button onclick="setPrimary({{ $media->id }})" class="p-1 text-amber-500 hover:bg-amber-50 rounded" title="Principal">
                                <i class="fas fa-star"></i>
                            </button>
                        @else
                            <button onclick="setPrimary({{ $media->id }})" class="p-1 text-gray-300 hover:text-amber-500 hover:bg-amber-50 rounded" title="Définir comme principal">
                                <i class="far fa-star"></i>
                            </button>
                        @endif
                        <form action="{{ route('admin.medias.destroy', $media) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce média ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-red-300 hover:text-red-500 hover:bg-red-50 rounded" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-1">
                    <i class="fas fa-image mr-1"></i>
                    {{ $media->model_type::find($media->model_id)?->titre ?? 'Orphelin' }}
                </p>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-gray-500">
            <i class="fas fa-photo-video text-4xl block mb-3"></i>
            <p>Aucun média trouvé</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        {{ $medias->links() }}
    </div>
</div>

@push('scripts')
<script>
    function setPrimary(id) {
        fetch(`/admin/medias/${id}/set-primary`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
</script>
@endpush
@endsection