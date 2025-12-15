{{-- resources/views/livewire/report-centre-impact-finance.blade.php --}}
<div>
    <!-- En-tête -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">🏢 Rapport Intégré Centres</h1>
        <p class="text-gray-600">Analyse combinée des impacts sociaux et des ressources financières par centre</p>
    </div>

    <!-- Statistiques Globales -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold">{{ number_format($globalStats['total_centres'] ?? 0, 0, ',', ' ') }}</div>
            <div class="text-sm opacity-90">Centres Totaux</div>
            <div class="text-xs mt-1 opacity-75">
                {{ $impactStats['centres_actifs'] ?? 0 }} centres actifs
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold">{{ number_format($globalStats['total_beneficiaires'] ?? 0, 0, ',', ' ') }}</div>
            <div class="text-sm opacity-90">Bénéficiaires Totaux</div>
            <div class="text-xs mt-1 opacity-75">
                {{ $globalStats['taux_feminisation'] ?? 0 }}% femmes
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold">{{ number_format($globalStats['total_recettes'] ?? 0, 0, ',', ' ') }} DH</div>
            <div class="text-sm opacity-90">Recettes Totales</div>
            <div class="text-xs mt-1 opacity-75">
                {{ $financeStats['centres_finances'] ?? 0 }} centres financés
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold">{{ number_format($globalStats['solde_global'] ?? 0, 0, ',', ' ') }} DH</div>
            <div class="text-sm opacity-90">Solde Global</div>
            <div class="text-xs mt-1 opacity-75">
                Taux dépenses: {{ $globalStats['taux_depenses'] ?? 0 }}%
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Graphique 1: Comparaison Centres -->
        <div class="bg-white p-5 rounded-xl shadow">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <span class="text-blue-600 text-xl">📊</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-800">Top 8 Centres</h3>
                    <p class="text-sm text-gray-500">Bénéficiaires vs Recettes</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="chart1"></canvas>
            </div>
        </div>
        
        <!-- Graphique 2: Performance Centres -->
        <div class="bg-white p-5 rounded-xl shadow">
            <div class="flex items-center mb-4">
                <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                    <span class="text-yellow-600 text-xl">⭐</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-800">Performance Top 3</h3>
                    <p class="text-sm text-gray-500">Analyse radar multidimensionnelle</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="chart2"></canvas>
            </div>
        </div>
        
        <!-- Graphique 3: Répartition Score -->
        <div class="bg-white p-5 rounded-xl shadow">
            <div class="flex items-center mb-4">
                <div class="bg-purple-100 p-2 rounded-lg mr-3">
                    <span class="text-purple-600 text-xl">📈</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-800">Distribution Scores</h3>
                    <p class="text-sm text-gray-500">Classification des centres</p>
                </div>
            </div>
            <div class="h-64">
                <canvas id="chart3"></canvas>
            </div>
        </div>
    </div>

    <!-- Tableau Principal -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800">📋 Analyse Détaillée par Centre</h3>
                <p class="text-gray-600">Performances combinées impact + finances</p>
            </div>
            <div class="text-sm text-gray-500">
                <span class="font-bold text-lg text-blue-600">{{ count($centresStats) }}</span> centres analysés
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left font-medium text-gray-700">Centre</th>
                        <th class="p-4 text-left font-medium text-gray-700">Impact Social</th>
                        <th class="p-4 text-left font-medium text-gray-700">Finances</th>
                        <th class="p-4 text-left font-medium text-gray-700">Scores</th>
                        <th class="p-4 text-left font-medium text-gray-700">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($centresStats as $centre)
                    <tr class="hover:bg-gray-50">
                        <!-- Colonne Centre -->
                        <td class="p-4">
                            <div class="font-medium text-gray-800">{{ $centre['nom'] }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $centre['ville'] }} • {{ $centre['code'] }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                @if($centre['has_impact'] && $centre['has_finance'])
                                    <span class="text-green-600">✓ Données complètes</span>
                                @elseif($centre['has_impact'])
                                    <span class="text-blue-600">✓ Impact seulement</span>
                                @elseif($centre['has_finance'])
                                    <span class="text-purple-600">✓ Finances seulement</span>
                                @else
                                    <span class="text-gray-400">⨯ Données manquantes</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Colonne Impact Social -->
                        <td class="p-4">
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Bénéficiaires:</span>
                                    <span class="font-medium">{{ number_format($centre['total_beneficiaires'], 0, ',', ' ') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Femmes:</span>
                                    <span class="font-medium">{{ $centre['taux_feminisation'] }}%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Coût moyen:</span>
                                    <span class="font-medium">{{ number_format($centre['cout_moyen'], 0, ',', ' ') }} DH</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Heures:</span>
                                    <span class="font-medium">{{ $centre['heures_moyennes'] }}h</span>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Colonne Finances -->
                        <td class="p-4">
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Recettes:</span>
                                    <span class="font-medium text-green-600">{{ number_format($centre['total_recettes'], 0, ',', ' ') }} DH</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Dépenses:</span>
                                    <span class="font-medium text-red-600">{{ number_format($centre['total_depenses'], 0, ',', ' ') }} DH</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Solde:</span>
                                    <span class="font-medium 
                                        @if($centre['solde'] >= 0) text-blue-600
                                        @else text-orange-600
                                        @endif">
                                        {{ number_format($centre['solde'], 0, ',', ' ') }} DH
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Taux dép.:</span>
                                    <span class="font-medium">{{ $centre['taux_depenses'] }}%</span>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Colonne Scores -->
                        <td class="p-4">
                            <div class="space-y-2">
                                <!-- Score Impact -->
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Impact</span>
                                        <span class="font-medium">{{ $centre['score_impact'] }}/100</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-blue-500" 
                                             style="width: {{ $centre['score_impact'] }}%"></div>
                                    </div>
                                </div>
                                
                                <!-- Score Finance -->
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Finance</span>
                                        <span class="font-medium">{{ $centre['score_finance'] }}/100</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-green-500" 
                                             style="width: {{ $centre['score_finance'] }}%"></div>
                                    </div>
                                </div>
                                
                                <!-- Score Global -->
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-800">Global</span>
                                        <span class="font-bold">{{ $centre['score_global'] }}/100</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full 
                                            @if($centre['score_global'] >= 70) bg-gradient-to-r from-green-500 to-green-600
                                            @elseif($centre['score_global'] >= 50) bg-gradient-to-r from-yellow-500 to-yellow-600
                                            @else bg-gradient-to-r from-red-500 to-red-600
                                            @endif" 
                                            style="width: {{ $centre['score_global'] }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Colonne Statut -->
                        <td class="p-4">
                            @php
                                $statusConfig = [
                                    'excellent' => ['color' => 'green', 'icon' => '🏆', 'label' => 'Excellent'],
                                    'bon' => ['color' => 'blue', 'icon' => '👍', 'label' => 'Bon'],
                                    'moyen' => ['color' => 'yellow', 'icon' => '⚖️', 'label' => 'Moyen'],
                                    'deficitaire' => ['color' => 'orange', 'icon' => '⚠️', 'label' => 'Déficitaire'],
                                    'inactif' => ['color' => 'gray', 'icon' => '⏸️', 'label' => 'Inactif']
                                ];
                                $status = $statusConfig[$centre['status']] ?? $statusConfig['moyen'];
                            @endphp
                            
                            <div class="flex flex-col items-center">
                                <span class="text-2xl mb-1">{{ $status['icon'] }}</span>
                                <span class="px-3 py-1 text-xs rounded-full 
                                    @if($status['color'] == 'green') bg-green-100 text-green-800
                                    @elseif($status['color'] == 'blue') bg-blue-100 text-blue-800
                                    @elseif($status['color'] == 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($status['color'] == 'orange') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800
                                    @endif font-medium">
                                    {{ $status['label'] }}
                                </span>
                                
                                <!-- Rank -->
                                @if($loop->iteration <= 3)
                                <div class="mt-2 text-xs px-2 py-1 rounded-full 
                                    @if($loop->iteration == 1) bg-yellow-100 text-yellow-800
                                    @elseif($loop->iteration == 2) bg-gray-100 text-gray-800
                                    @elseif($loop->iteration == 3) bg-orange-100 text-orange-800
                                    @endif">
                                    #{{ $loop->iteration }}
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tableaux de Synthèse -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Top 5 Centres -->
        <div class="bg-white rounded-xl shadow p-6">
            <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                <span class="mr-2">🏆</span> Top 5 Centres
            </h4>
            <div class="space-y-4">
                @foreach(array_slice($centresStats, 0, 5) as $index => $centre)
                <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full 
                            @if($index == 0) bg-yellow-100 text-yellow-800
                            @elseif($index == 1) bg-gray-100 text-gray-800
                            @elseif($index == 2) bg-orange-100 text-orange-800
                            @else bg-blue-100 text-blue-800
                            @endif font-bold mr-3">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div class="font-medium">{{ $centre['nom'] }}</div>
                            <div class="text-sm text-gray-500">{{ $centre['ville'] }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-lg">{{ $centre['score_global'] }}/100</div>
                        <div class="text-xs text-gray-500">
                            {{ number_format($centre['total_beneficiaires'], 0, ',', ' ') }} bénéf.
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Indicateurs Clés -->
        <div class="bg-white rounded-xl shadow p-6">
            <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                <span class="mr-2">📊</span> Indicateurs Clés
            </h4>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Centres avec données complètes</span>
                        <span class="font-medium">
                            {{ collect($centresStats)->where('has_impact', true)->where('has_finance', true)->count() }}
                            / {{ count($centresStats) }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $complet = collect($centresStats)->where('has_impact', true)->where('has_finance', true)->count();
                            $pourcentage = count($centresStats) > 0 ? ($complet / count($centresStats)) * 100 : 0;
                        @endphp
                        <div class="h-2 rounded-full bg-green-500" style="width: {{ $pourcentage }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Centres excédentaires</span>
                        <span class="font-medium">
                            {{ collect($centresStats)->where('solde', '>', 0)->count() }}
                            / {{ count($centresStats) }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $excedent = collect($centresStats)->where('solde', '>', 0)->count();
                            $pourcentageExcedent = count($centresStats) > 0 ? ($excedent / count($centresStats)) * 100 : 0;
                        @endphp
                        <div class="h-2 rounded-full bg-blue-500" style="width: {{ $pourcentageExcedent }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Score moyen global</span>
                        <span class="font-medium">
                            {{ round(collect($centresStats)->avg('score_global') ?? 0, 1) }}/100
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $scoreMoyen = collect($centresStats)->avg('score_global') ?? 0;
                        @endphp
                        <div class="h-2 rounded-full 
                            @if($scoreMoyen >= 70) bg-green-500
                            @elseif($scoreMoyen >= 50) bg-yellow-500
                            @else bg-red-500
                            @endif" 
                            style="width: {{ $scoreMoyen }}%">
                        </div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Bénéficiaires par centre (moy.)</span>
                        <span class="font-medium">{{ round($impactStats['moyenne_par_centre'] ?? 0, 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $benefMoyen = min(100, ($impactStats['moyenne_par_centre'] ?? 0) / 2);
                        @endphp
                        <div class="h-2 rounded-full bg-purple-500" style="width: {{ $benefMoyen }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommandations -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow p-6">
        <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
            <span class="mr-2">💡</span> Recommandations Stratégiques
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
                $centresDeficit = collect($centresStats)->where('solde', '<', 0)->count();
                $centresInactifs = collect($centresStats)->where('total_beneficiaires', 0)->count();
                $scoreMoyen = collect($centresStats)->avg('score_global') ?? 0;
            @endphp
            
            @if($centresDeficit > 0)
            <div class="bg-white p-4 rounded-lg border border-red-200">
                <div class="flex items-start">
                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                        <span class="text-red-600">⚠️</span>
                    </div>
                    <div>
                        <div class="font-medium text-gray-800">Centres Déficitaires</div>
                        <div class="text-sm text-gray-600 mt-1">
                            {{ $centresDeficit }} centre(s) présentent un solde négatif.
                            Recommandation: Revue des dépenses et optimisation budgétaire.
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if($centresInactifs > 0)
            <div class="bg-white p-4 rounded-lg border border-yellow-200">
                <div class="flex items-start">
                    <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                        <span class="text-yellow-600">⏸️</span>
                    </div>
                    <div>
                        <div class="font-medium text-gray-800">Centres Inactifs</div>
                        <div class="text-sm text-gray-600 mt-1">
                            {{ $centresInactifs }} centre(s) n'ont pas d'activités d'impact.
                            Recommandation: Relancer les programmes ou réaffecter les ressources.
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if($scoreMoyen < 60)
            <div class="bg-white p-4 rounded-lg border border-orange-200">
                <div class="flex items-start">
                    <div class="bg-orange-100 p-2 rounded-lg mr-3">
                        <span class="text-orange-600">📉</span>
                    </div>
                    <div>
                        <div class="font-medium text-gray-800">Performance Moyenne</div>
                        <div class="text-sm text-gray-600 mt-1">
                            Score moyen: {{ round($scoreMoyen, 1) }}/100.
                            Recommandation: Programmes de formation et meilleures pratiques.
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if(collect($centresStats)->where('score_global', '>=', 80)->count() > 0)
            <div class="bg-white p-4 rounded-lg border border-green-200">
                <div class="flex items-start">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <span class="text-green-600">✅</span>
                    </div>
                    <div>
                        <div class="font-medium text-gray-800">Centres Performants</div>
                        <div class="text-sm text-gray-600 mt-1">
                            {{ collect($centresStats)->where('score_global', '>=', 80)->count() }} centre(s) excellent.
                            Recommandation: Capitaliser sur leurs meilleures pratiques.
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialiser tous les graphiques
    function initCharts() {
        // Données des graphiques
        const comparisonStats = @js($comparisonStats);
        const performanceStats = @js($performanceStats);
        const centresStats = @js($centresStats);
        
        // Graphique 1: Comparaison Centres
        const ctx1 = document.getElementById('chart1');
        if (ctx1 && comparisonStats.labels.length > 0) {
            new Chart(ctx1, {
                type: 'bar',
                data: comparisonStats,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value >= 1000 ? (value/1000) + 'k' : value;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Graphique 2: Performance Radar
        const ctx2 = document.getElementById('chart2');
        if (ctx2 && performanceStats.datasets.length > 0) {
            new Chart(ctx2, {
                type: 'radar',
                data: performanceStats,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 100,
                            ticks: { stepSize: 20 }
                        }
                    },
                    elements: {
                        line: { borderWidth: 3 }
                    }
                }
            });
        }
        
        // Graphique 3: Distribution Scores
        const ctx3 = document.getElementById('chart3');
        if (ctx3) {
            const scores = centresStats.map(c => c.score_global);
            const ranges = [
                { min: 0, max: 40, label: 'Faible', color: '#EF4444' },
                { min: 41, max: 60, label: 'Moyen', color: '#F59E0B' },
                { min: 61, max: 80, label: 'Bon', color: '#3B82F6' },
                { min: 81, max: 100, label: 'Excellent', color: '#10B981' }
            ];
            
            const counts = ranges.map(range => 
                scores.filter(score => score >= range.min && score <= range.max).length
            );
            
            new Chart(ctx3, {
                type: 'pie',
                data: {
                    labels: ranges.map(r => r.label),
                    datasets: [{
                        data: counts,
                        backgroundColor: ranges.map(r => r.color),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        }
    }
    
    // Initialiser quand la page est chargée
    document.addEventListener('DOMContentLoaded', initCharts);
</script>
