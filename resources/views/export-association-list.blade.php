<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Liste des Associations</title>
    <style>
        body { font-family: DejaVu Sans, DejaVuSans, Arial, sans-serif; font-size:12px; color:#222 }
        h1 { font-size:18px; margin:0 0 8px }
        .meta { font-size:11px; color:#555; margin-bottom:8px }
        table { width:100%; border-collapse:collapse; margin-top:6px }
        th, td { border:1px solid #ddd; padding:6px; vertical-align:top }
        th { background:#f5f5f5; font-weight:600; font-size:11px }
        td { font-size:11px }
    </style>
</head>
<body>
    <h1>Liste des Associations</h1>
    @if(!empty($filters))
        <div class="meta">Filtres appliqués:
            @foreach($filters as $k => $v)
                @if($v !== null && $v !== '')
                    <strong>{{ $k }}</strong>: {{ $v }}&nbsp;&nbsp;
                @endif
            @endforeach
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email / Tél</th>
                <th>Président</th>
                <th>Secteur</th>
                <th>District</th>
                <th>Bénéficiaires</th>
                <th>Employés</th>
                <th>Créé</th>
            </tr>
        </thead>
        <tbody>
            @foreach($associations as $a)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $a->nom_de_l_asso }}@if($a->nom_asso_ar)\n{{ $a->nom_asso_ar }}@endif</td>
                    <td>{{ $a->email }} @if($a->tel) / {{ $a->tel }} @endif</td>
                    <td>{{ $a->president_name ?? '-' }}</td>
                    <td>{{ $a->secteur->nom_secteur_fr ?? '-' }}</td>
                    <td>{{ $a->district->nom_district_fr ?? '-' }}</td>
                    <td>{{ $a->nombreBeneficiaire ?? 0 }}</td>
                    <td>{{ $a->nombre_employes ?? 0 }}</td>
                    <td>{{ $a->date_de_creation ? $a->date_de_creation->format('d/m/Y') : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
