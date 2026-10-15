{{-- resources/views/public/ouvrages/print.blade.php — fiche imprimable (route ouvrages.print) --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $ouvrage->reference }} — {{ $ouvrage->nom }}</title>
    <style>
        body { font: 12px/1.4 Arial, sans-serif; color: #111; margin: 24px; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        h2 { font-size: 13px; margin: 18px 0 6px; text-transform: uppercase; letter-spacing: .08em; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        th { background: #eee; }
        .meta { color: #555; margin-bottom: 12px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <p class="no-print"><a href="{{ route('ouvrages.show', $ouvrage) }}">← Retour</a> · <a href="#" onclick="window.print();return false;">Imprimer</a></p>

    <h1>{{ $ouvrage->nom }}</h1>
    <div class="meta">
        Réf. {{ $ouvrage->reference }}
        @if($ouvrage->categorie) · {{ $ouvrage->categorie->nom }} @endif
    </div>

    @if($ouvrage->description_courte)<p>{{ $ouvrage->description_courte }}</p>@endif

    @if($ouvrage->caracteristiques->isNotEmpty())
        <h2>Caractéristiques</h2>
        <table>
            @foreach($ouvrage->caracteristiques as $car)
                <tr><th style="width:35%">{{ $car->nom ?? $car->libelle ?? '' }}</th><td>{{ $car->valeur ?? '' }}</td></tr>
            @endforeach
        </table>
    @endif

    <h2>Composition ({{ $ouvrage->composants->count() }})</h2>
    <table>
        <thead><tr><th>#</th><th>Référence</th><th>Désignation</th><th>Qté</th></tr></thead>
        <tbody>
        @foreach($ouvrage->composants as $c)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $c->reference }}</td>
                <td>{{ $c->designation }}</td>
                <td>{{ rtrim(rtrim(number_format((float) $c->pivot->quantite, 2, ',', ' '), '0'), ',') }} {{ $c->pivot->unite }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
