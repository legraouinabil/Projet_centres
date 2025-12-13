<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des Centres</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px; 
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 2px solid #333; 
            padding-bottom: 15px; 
        }
        .title { 
            font-size: 22px; 
            font-weight: bold; 
            color: #2c5282; 
            margin-bottom: 5px;
        }
        .subtitle { 
            font-size: 14px; 
            color: #666; 
            margin-bottom: 5px;
        }
        .filters {
            background: #f7fafc;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #4299e1;
        }
        .stats { 
            margin: 25px 0; 
        }
        .stats h3 {
            color: #2d3748;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .stat-item { 
            margin: 10px 0; 
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
        }
        .stat-label { 
            font-weight: bold; 
            color: #2d3748; 
        }
        .stat-value { 
            color: #4a5568; 
            font-weight: 600;
        }
        .footer { 
            margin-top: 40px; 
            border-top: 1px solid #cbd5e0; 
            padding-top: 15px; 
            text-align: center; 
            color: #718096; 
            font-size: 10px; 
        }
        .page-break {
            page-break-before: always;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table th, .table td {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            text-align: left;
        }
        .table th {
            background: #edf2f7;
            font-weight: bold;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">RAPPORT DES CENTRES</div>
        <div class="subtitle">Système de Gestion des Centres d'Intervention Sociale</div>
        <div class="subtitle">Généré le {{ $date }}</div>
        
        @if($domaine)
        <div class="filters">
            <strong>Filtres appliqués:</strong><br>
            Domaine: {{ $domaineLabel }}<br>
            Type de rapport: {{ ucfirst($type) }}
        </div>
        @endif
    </div>

    <div class="stats">
        <h3>📊 STATISTIQUES GLOBALES</h3>
        
        @foreach($data['globalStats'] as $key => $value)
        <div class="stat-item">
            <span class="stat-label">{{ $statLabels[$key] ?? $key }}:</span>
            <span class="stat-value">
                @if(in_array($key, ['masse_salariale', 'budget_total']))
                    {{ number_format($value, 0, ',', ' ') }} DH
                @elseif(in_array($key, ['total_beneficiaires']))
                    {{ number_format($value, 0, ',', ' ') }} personnes
                @else
                    {{ $value }}
                @endif
            </span>
        </div>
        @endforeach
    </div>

    <!-- Section Résumé -->
    <div class="stats">
        <h3>📈 RÉSUMÉ DES PERFORMANCES</h3>
        
        @php
            $stats = $data['globalStats'];
            $efficacite = $stats['budget_total'] > 0 ? (($stats['budget_total'] - ($stats['masse_salariale'] ?? 0)) / $stats['budget_total']) * 100 : 0;
            $coutParBeneficiaire = $stats['total_beneficiaires'] > 0 ? $stats['budget_total'] / $stats['total_beneficiaires'] : 0;
        @endphp
        
        <div class="stat-item">
            <span class="stat-label">Efficacité budgétaire:</span>
            <span class="stat-value">{{ number_format($efficacite, 1) }}%</span>
        </div>
        
        <div class="stat-item">
            <span class="stat-label">Coût moyen par bénéficiaire:</span>
            <span class="stat-value">{{ number_format($coutParBeneficiaire, 0, ',', ' ') }} DH</span>
        </div>
        
        <div class="stat-item">
            <span class="stat-label">Ratio personnel/centres:</span>
            <span class="stat-value">
                @if($stats['total_centres'] > 0)
                    {{ number_format($stats['total_personnel'] / $stats['total_centres'], 1) }} pers/centre
                @else
                    N/A
                @endif
            </span>
        </div>
    </div>

    <!-- Tableau détaillé -->
    <div class="stats">
        <h3>📋 DÉTAIL DES INDICATEURS</h3>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Indicateur</th>
                    <th>Valeur</th>
                    <th>Unité</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nombre de centres</td>
                    <td>{{ $stats['total_centres'] }}</td>
                    <td>centres</td>
                </tr>
                <tr>
                    <td>Effectif total</td>
                    <td>{{ $stats['total_personnel'] }}</td>
                    <td>personnes</td>
                </tr>
                <tr>
                    <td>Bénéficiaires accompagnés</td>
                    <td>{{ number_format($stats['total_beneficiaires'], 0, ',', ' ') }}</td>
                    <td>personnes</td>
                </tr>
                <tr>
                    <td>Masse salariale</td>
                    <td>{{ number_format($stats['masse_salariale'], 0, ',', ' ') }}</td>
                    <td>DH</td>
                </tr>
                <tr>
                    <td>Budget total</td>
                    <td>{{ number_format($stats['budget_total'], 0, ',', ' ') }}</td>
                    <td>DH</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <strong>Système de Gestion des Centres</strong><br>
        Document généré automatiquement le {{ $date }}<br>
        Pour toute information complémentaire, contactez l'administration
    </div>
</body>
</html>