<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .header { text-align: center; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #f5f5f5; font-weight: 700; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Liste des Centres</h2>
        <div>{{ $date ?? now()->format('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Centre</th>
                <th>Domaine</th>
                <th>Localisation</th>
                <th>Superficie (m²)</th>
                <th>Associations</th>
            </tr>
        </thead>
        <tbody>
            @foreach($centres as $centre)
                <tr>
                    <td>{{ $centre->denomination }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $centre->domaine_intervention)) }}</td>
                    <td>{{ $centre->localisation ?? 'N/A' }}</td>
                    <td>{{ $centre->superficie ? number_format($centre->superficie, 0, ',', ' ') : '-' }}</td>
                    <td>
                        @if($centre->associations && $centre->associations->count())
                            {{ $centre->associations->pluck('nom_de_l_asso')->join(', ') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
