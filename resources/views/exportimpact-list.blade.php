<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Export des Impacts</title>
    @php
        $logoPath = public_path('images/logo.png');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
        $primary = '#8B0000'; // Rouge foncé
        $primaryLight = '#fff5f5';
        $accent = '#006400'; // Vert foncé
        $gray = '#f4f4f4';
    @endphp
    <style>
        @page { margin: 20px 25px; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10px; /* Taille légèrement réduite pour les listes denses */
            color: #333; 
            margin: 0;
        }

        /* Layout du Header */
        .header-table { width: 100%; border-bottom: 3px solid {{ $primary }}; margin-bottom: 15px; padding-bottom: 10px; }
        .logo-box { width: 64px; height: 64px; }
        .logo-placeholder { 
            width: 60px; height: 60px; background: {{ $primary }}; 
            color: white; border-radius: 4px; line-height: 60px; text-align: center; font-weight: bold; 
        }
        .info-box { padding-left: 15px; }
        .title { font-size: 18px; font-weight: bold; color: {{ $primary }}; text-transform: uppercase; margin: 0; }
        .subtitle { font-size: 11px; color: {{ $accent }}; font-weight: bold; }

        /* Filtres */
        .filter-section { 
            background: {{ $gray }}; 
            padding: 8px 12px; 
            border-radius: 4px; 
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
        }
        .filter-tag { display: inline-block; margin-right: 15px; font-size: 9px; }
        .filter-label { color: #666; text-transform: uppercase; font-weight: bold; margin-right: 3px; }

        /* Tableau de données */
        .data-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .data-table th { 
            background: {{ $primary }}; 
            color: white; 
            text-align: left; 
            padding: 8px 5px; 
            font-weight: bold;
            border: 1px solid {{ $primary }};
        }
        .data-table td { 
            padding: 7px 5px; 
            border-bottom: 1px solid #eee;
            vertical-align: top;
            word-wrap: break-word;
        }
        
        /* Zebra striping */
        .data-table tr:nth-child(even) { background-color: {{ $primaryLight }}; }

        /* Colonnes spécifiques */
        .col-centre { width: 18%; }
        .col-annee { width: 7%; text-align: center; }
        .col-filiere { width: 30%; }
        .col-inscrits { width: 15%; }
        .col-abandons { width: 10%; text-align: center; }
        .col-cout { width: 20%; text-align: right; font-weight: bold; }

        .text-muted { color: #777; font-size: 9px; }
        .badge { font-weight: bold; color: {{ $primary }}; }
        
        .footer { 
            position: fixed; bottom: -10px; width: 100%; 
            font-size: 8px; color: #999; text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="logo-box">
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" style="width:60px; height:60px; object-fit:contain" />
                @else
                    <div class="logo-placeholder">LOGO</div>
                @endif
            </td>
            <td class="info-box">
                <div class="title">Rapport des Impacts</div>
                <div class="subtitle">Export de la liste consolidée</div>
                <div class="text-muted">Document généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
            </td>
        </tr>
    </table>

    <!-- Filtres appliqués (si présents) -->
    @if(!empty($filters) && count(array_filter($filters)) > 0)
    <div class="filter-section">
        @foreach($filters as $k => $v)
            @if($v)
                <span class="filter-tag">
                    <span class="filter-label">{{ str_replace('_', ' ', $k) }}:</span> 
                    <span class="filter-value">{{ $v }}</span>
                </span>
            @endif
        @endforeach
    </div>
    @endif

    <!-- Tableau -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-centre">Centre</th>
                <th class="col-annee">Année</th>
                <th class="col-filiere">Filière / Domaine</th>
                <th class="col-inscrits">Inscrits</th>
                <th class="col-abandons">Abandons</th>
                <th class="col-cout">Coût / pers</th>
            </tr>
        </thead>
        <tbody>
            @foreach($impacts as $impact)
                <tr>
                    <td class="bold">{{ $impact->centre->denomination }}</td>
                    <td class="text-center">{{ $impact->annee }}</td>
                    <td>
                        <strong>{{ $impact->intitule_filiere_discipline }}</strong><br>
                        <span class="text-muted">{{ $impact->domaine_label }}</span>
                    </td>
                    <td>
                        <span class="badge">{{ $impact->total_inscrits }} total</span><br>
                        <span class="text-muted">{{ $impact->nombre_inscrits_hommes }}H / {{ $impact->nombre_inscrits_femmes }}F</span>
                    </td>
                    <td class="text-center">
                        {{ $impact->nombre_abandons }}
                        @if($impact->total_inscrits > 0)
                            <div class="text-muted">({{ round(($impact->nombre_abandons / $impact->total_inscrits) * 100, 1) }}%)</div>
                        @endif
                    </td>
                    <td class="col-cout">
                        <span style="color:{{ $accent }}">{{ number_format($impact->cout_revient_par_beneficiaire, 2) }} DH</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Page <script type="text/php">echo $PAGE_NUM . " / " . $PAGE_COUNT;</script> — Liste des impacts — {{ date('d/m/Y') }}
    </div>

</body>
</html>