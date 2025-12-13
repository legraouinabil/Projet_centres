{{-- resources/views/livewire/statistique-gestion-impact.blade.php --}}
<div>
    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Centre</label>
                <select wire:model.live="centre_id" class="w-full border-gray-300 rounded-md">
                    <option value="">Tous</option>
                    @foreach($centres as $centre)
                        <option value="{{ $centre->id }}">{{ $centre->denomination }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                <select wire:model.live="annee" class="w-full border-gray-300 rounded-md">
                    @for($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Domaine</label>
                <select wire:model.live="domaine" class="w-full border-gray-300 rounded-md">
                    <option value="">Tous</option>
                    <option value="formation_pro">Formation Pro</option>
                    <option value="animation_culturelle_sportive">Animation</option>
                    <option value="handicap">Handicap</option>
                    <option value="eps">EPS</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
                <button wire:click="resetFilters" class="w-full bg-gray-100 text-gray-700 py-2 rounded-md hover:bg-gray-200">
                    Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_beneficiaires'] ?? 0 }}</div>
            <div class="text-sm text-gray-600">Bénéficiaires</div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ $stats['taux_feminisation'] ?? 0 }}%</div>
            <div class="text-sm text-gray-600">Féminisation</div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['cout_moyen'] ?? 0 }} DH</div>
            <div class="text-sm text-gray-600">Coût moyen</div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-orange-600">{{ $stats['total_impacts'] ?? 0 }}</div>
            <div class="text-sm text-gray-600">Impacts</div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Graphique 1 -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-bold mb-3">Répartition par domaine</h3>
            <div class="h-64">
                <canvas id="chart1"></canvas>
            </div>
        </div>
        
        <!-- Graphique 2 -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-bold mb-3">Top centres</h3>
            <div class="h-64">
                <canvas id="chart2"></canvas>
            </div>
        </div>
        
        <!-- Graphique 3 -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-bold mb-3">Évolution</h3>
            <div class="h-64">
                <canvas id="chart3"></canvas>
            </div>
        </div>
        
        <!-- Graphique 4 -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="font-bold mb-3">Répartition H/F</h3>
            <div class="h-64">
                <canvas id="chart4"></canvas>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-bold mb-3">Détails par domaine</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left">Domaine</th>
                        <th class="p-2 text-left">Impacts</th>
                        <th class="p-2 text-left">Bénéficiaires</th>
                        <th class="p-2 text-left">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($domainesStats as $stat)
                    <tr class="border-b">
                        <td class="p-2">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $stat['color'] }}"></div>
                                {{ $stat['label'] }}
                            </div>
                        </td>
                        <td class="p-2">{{ $stat['count'] }}</td>
                        <td class="p-2">{{ $stat['total'] }}</td>
                        <td class="p-2">
                            @if($stats['total_beneficiaires'] > 0)
                                {{ round(($stat['total'] / $stats['total_beneficiaires']) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let chart1, chart2, chart3, chart4;
        
        // Fonction pour créer les graphiques
        function createCharts() {
            // Détruire les anciens graphiques
            [chart1, chart2, chart3, chart4].forEach(chart => {
                if (chart) chart.destroy();
            });
            
            // Graphique 1 - Pie Chart
            const ctx1 = document.getElementById('chart1');
            if (ctx1) {
                const labels1 = @js($domainesStats).map(item => item.label);
                const data1 = @js($domainesStats).map(item => item.total);
                const colors1 = @js($domainesStats).map(item => item.color);
                
                chart1 = new Chart(ctx1, {
                    type: 'pie',
                    data: {
                        labels: labels1,
                        datasets: [{
                            data: data1,
                            backgroundColor: colors1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
            
            // Graphique 2 - Bar Chart
            const ctx2 = document.getElementById('chart2');
            if (ctx2) {
                const labels2 = @js($centresStats).map(item => item.name);
                const data2 = @js($centresStats).map(item => item.total);
                
                chart2 = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: labels2,
                        datasets: [{
                            label: 'Bénéficiaires',
                            data: data2,
                            backgroundColor: '#36A2EB'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            
            // Graphique 3 - Line Chart
            const ctx3 = document.getElementById('chart3');
            if (ctx3) {
                const labels3 = @js($evolutionStats).map(item => item.annee);
                const data3 = @js($evolutionStats).map(item => item.total_beneficiaires);
                
                chart3 = new Chart(ctx3, {
                    type: 'line',
                    data: {
                        labels: labels3,
                        datasets: [{
                            label: 'Bénéficiaires',
                            data: data3,
                            borderColor: '#FF6384',
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            
            // Graphique 4 - Doughnut Chart
            const ctx4 = document.getElementById('chart4');
            if (ctx4) {
                const totalHommes = @js($stats['total_hommes'] ?? 0);
                const totalFemmes = @js($stats['total_femmes'] ?? 0);
                
                chart4 = new Chart(ctx4, {
                    type: 'doughnut',
                    data: {
                        labels: ['Hommes', 'Femmes'],
                        datasets: [{
                            data: [totalHommes, totalFemmes],
                            backgroundColor: ['#36A2EB', '#FF6384']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        }
        
        // Initialiser les graphiques quand la page est chargée
        document.addEventListener('DOMContentLoaded', function() {
            createCharts();
        });
        
        // Réinitialiser les graphiques quand Livewire met à jour les données
        window.Livewire.on('updated', () => {
            setTimeout(createCharts, 100);
        });
    </script>
    
</div>