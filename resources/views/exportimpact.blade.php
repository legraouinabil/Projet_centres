<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Impact #{{ $impact->id }}</title>
    @php
        $logoPath = public_path('images/logo.png');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
        $primary = '#8B0000'; 
        $primaryLight = '#fff1f1';
        $accent = '#006400';
        $accentLight = '#f0fff0';
        $gray = '#f8f9fa';
    @endphp
    <style>
        @page { margin: 30px; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            color: #333; 
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        
        /* Layout Helpers */
        .w-100 { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        
        /* Header */
        .header-table { width: 100%; border-bottom: 2px solid {{ $primary }}; padding-bottom: 15px; margin-bottom: 20px; }
        .logo-container { width: 80px; }
        .logo-placeholder { 
            width: 70px; height: 70px; background: {{ $primary }}; 
            color: white; border-radius: 8px; line-height: 70px; text-align: center; font-weight: bold; 
        }
        .title-container { padding-left: 15px; }
        .main-title { font-size: 20px; font-weight: bold; color: {{ $primary }}; margin: 0; text-transform: uppercase; }
        .sub-title { font-size: 13px; color: #555; margin-top: 5px; }

        /* KPI Cards */
        .kpi-table { width: 100%; margin-bottom: 20px; border-spacing: 10px 0; margin-left: -10px; }
        .kpi-card { 
            background: {{ $gray }}; 
            padding: 12px; 
            border-radius: 8px; 
            text-align: center; 
            border-bottom: 3px solid #ddd;
        }
        .kpi-red { border-bottom-color: {{ $primary }}; background: {{ $primaryLight }}; }
        .kpi-green { border-bottom-color: {{ $accent }}; background: {{ $accentLight }}; }
        .kpi-value { font-size: 16px; font-weight: bold; display: block; }
        .kpi-label { font-size: 10px; text-transform: uppercase; color: #666; margin-top: 4px; }

        /* Sections */
        .section-title { 
            background: {{ $gray }}; 
            padding: 6px 10px; 
            font-size: 12px; 
            font-weight: bold; 
            color: {{ $primary }}; 
            border-left: 4px solid {{ $primary }};
            margin-bottom: 10px;
            margin-top: 20px;
        }
        .details-table { width: 100%; margin-bottom: 10px; }
        .details-table td { padding: 4px 0; vertical-align: top; }
        .label { color: #666; width: 40%; }
        .value { color: #111; font-weight: bold; }

        /* Tables */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th { background: {{ $primary }}; color: white; padding: 8px; text-align: left; font-size: 10px; }
        .data-table td { border-bottom: 1px solid #eee; padding: 8px; font-size: 11px; }

        .footer { 
            position: fixed; bottom: 0; width: 100%; 
            font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 5px; 
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td class="logo-container">
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" style="width:70px; height:70px; object-fit:contain" />
                @else
                    <div class="logo-placeholder">LOGO</div>
                @endif
            </td>
            <td class="title-container">
                <div class="main-title">Fiche d'Impact</div>
                <div class="sub-title">
                    ID #{{ $impact->id }} — <strong>{{ $impact->centre->denomination }}</strong><br>
                    Secteur: {{ $impact->domaine_label }} • Année {{ $impact->annee }}
                </div>
            </td>
            <td class="text-right" style="vertical-align: bottom; color: #888; font-size: 10px;">
                Généré le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

    <!-- Quick Metrics (KPIs) -->
    <table class="kpi-table">
        <tr>
            <td width="33%">
                <div class="kpi-card kpi-red">
                    <span class="kpi-value" style="color: {{ $primary }}">{{ $impact->total_inscrits }}</span>
                    <span class="kpi-label">Inscrits Totaux</span>
                </div>
            </td>
            <td width="33%">
                <div class="kpi-card kpi-green">
                    <span class="kpi-value" style="color: {{ $accent }}">{{ number_format($impact->cout_revient_par_beneficiaire, 0) }} DH</span>
                    <span class="kpi-label">Coût / Bénéficiaire</span>
                </div>
            </td>
            <td width="33%">
                <div class="kpi-card">
                    <span class="kpi-value">{{ $impact->heures_par_beneficiaire }} h</span>
                    <span class="kpi-label">Volume Horaire</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Main Information -->
    <table class="w-100" style="border-spacing: 20px 0;">
        <tr>
            <!-- Left Column -->
            <td width="50%" style="vertical-align: top;">
                <div class="section-title">Informations Générales</div>
                <table class="details-table">
                    <tr><td class="label">Filière / Discipline</td><td class="value">{{ $impact->intitule_filiere_discipline }}</td></tr>
                    <tr><td class="label">Localisation</td><td class="value">{{ $impact->centre->localisation ?? '—' }}</td></tr>
                    <tr><td class="label">Bénéficiaires (H/F)</td><td class="value">{{ $impact->nombre_inscrits_hommes }} H / {{ $impact->nombre_inscrits_femmes }} F</td></tr>
                    <tr><td class="label">Abandons</td><td class="value">{{ $impact->nombre_abandons }}</td></tr>
                </table>
            </td>
            <!-- Right Column -->
            <td width="50%" style="vertical-align: top;">
                <div class="section-title">Analyse Financière</div>
                <table class="details-table">
                    <tr><td class="label">Masse salariale</td><td class="value">{{ number_format($impact->masse_salariale, 2) }} DH</td></tr>
                    <tr><td class="label">Charges globales</td><td class="value">{{ number_format($impact->charges_globales, 2) }} DH</td></tr>
                    <tr><td class="label">Coût Revient</td><td class="value">{{ number_format($impact->cout_revient_par_beneficiaire, 2) }} DH</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Conditional Domain Details -->
    <div class="section-title">Indicateurs Spécifiques</div>
    <table class="details-table">
        <tr>
            @if($impact->formationPro)
                <td width="33%">
                    <span class="bold" style="color:{{ $accent }}">Formation</span><br>
                    Lauréats: <strong>{{ $impact->formationPro->nombre_laureats }}</strong><br>
                    Insertion: <strong>{{ $impact->formationPro->taux_insertion_professionnelle }}%</strong>
                </td>
            @endif

            @if($impact->animation)
                <td width="33%">
                    <span class="bold" style="color:{{ $accent }}">Animation</span><br>
                    Événements: <strong>{{ $impact->animation->nombre_evenements_organises }}</strong><br>
                    Disciplines: <strong>{{ $impact->animation->nombre_disciplines }}</strong>
                </td>
            @endif

            @if($impact->handicap || $impact->eps)
                <td width="33%">
                    <span class="bold" style="color:{{ $accent }}">Inclusion/EPS</span><br>
                    Handicaps traités: <strong>{{ optional($impact->handicap)->nombre_handicaps_traites ?? optional($impact->eps)->nombre_handicaps_traites }}</strong>
                </td>
            @endif
        </tr>
    </table>

    <!-- Partners Table -->
    @if($impact->partenaires && $impact->partenaires->count())
        <div class="section-title">Partenaires Mobilisés</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom du Partenaire</th>
                    <th>Type de Partenariat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($impact->partenaires as $p)
                    <tr>
                        <td>{{ $p->nom }}</td>
                        <td>{{ $p->type ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Document officiel — Fiche d'Impact ID #{{ $impact->id }} — Page 1/1 
        <span style="float:right">Imprimé le {{ date('d/m/Y') }}</span>
    </div>

</body>
</html>