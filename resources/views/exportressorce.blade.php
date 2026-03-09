<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ressources Humaines</title>
    @php
        $logoPath = public_path('images/logo.png');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
        $primary = '#8B0000';
        $accent = '#006400';
    @endphp
    <style>
        @page { margin: 20px 24px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color:#111 }
        .header { display:flex; align-items:center; gap:12px; border-bottom:4px solid {{ $primary }}; padding-bottom:10px; margin-bottom:12px }
        .title { font-size:16px; font-weight:800; color:{{ $primary }} }
        .subtitle { color:{{ $accent }}; font-size:12px }
        table { width:100%; border-collapse:collapse; margin-top:10px }
        th, td { border:1px solid #e6e6e6; padding:8px; text-align:left }
        th { background:#f8f8f8; font-weight:700 }
    </style>
</head>
<body>
    <div class="header">
        @if($logoData)
            <img src="data:image/png;base64,{{ $logoData }}" style="width:64px;height:64px;object-fit:contain" />
        @else
            <div style="width:64px;height:64px;background:{{ $primary }};color:white;display:flex;align-items:center;justify-content:center;font-weight:700">LOGO</div>
        @endif
        <div>
            <div class="title">Ressources Humaines</div>
            <div class="subtitle">Export — Généré le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    @if(!empty($filters))
    <div style="font-size:11px;color:#555;margin-bottom:8px">Filtres:
        @foreach($filters as $k => $v)
            @if($v)
                <strong>{{ $k }}:</strong> {{ $v }}&nbsp;
            @endif
        @endforeach
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nom & Prénom</th>
                <th>Poste</th>
                <th>Centre</th>
                <th>Contrat</th>
                <th>Salaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rhs as $rh)
            <tr>
                <td>{{ $rh->nom_prenom }}</td>
                <td>{{ $rh->poste }}</td>
                <td>{{ $rh->centre->denomination ?? '—' }}</td>
                <td>{{ $rh->type_contrat }}</td>
                <td>{{ number_format($rh->salaire, 2, ',', ' ') }} DH</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
