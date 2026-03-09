{{-- resources/views/livewire/statistique-gestion-impact.blade.php --}}
<div class="py-6">
    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
        

       

        <!-- Graphiques (5 graphiques différents) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Graphique 1: Pie Chart -->
            <div class="bg-white p-5 rounded-xl shadow">
                <div class="flex items-center mb-4">
                    
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Répartition par Domaine</h3>
                        <p class="text-sm text-gray-500">Graphique circulaire</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="chart1"></canvas>
                </div>
            </div>

            <!-- Graphique 2: Bar Chart -->
            <div class="bg-white p-5 rounded-xl shadow">
                <div class="flex items-center mb-4">
                    
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Top 8 Centres</h3>
                        <p class="text-sm text-gray-500">Classement par bénéficiaires</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="chart2"></canvas>
                </div>
            </div>

            <!-- Graphique 3: Line Chart -->
            <div class="bg-white p-5 rounded-xl shadow">
                <div class="flex items-center mb-4">
                   
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Évolution 5 Ans</h3>
                        <p class="text-sm text-gray-500">Tendance temporelle</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="chart3"></canvas>
                </div>
            </div>

            <!-- Graphique 4: Doughnut Chart -->
            <div class="bg-white p-5 rounded-xl shadow">
                <div class="flex items-center mb-4">
                   
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Répartition Genre</h3>
                        <p class="text-sm text-gray-500">Hommes vs Femmes</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="chart4"></canvas>
                </div>
            </div>

            <!-- Graphique 5: Radar Chart -->
            <div class="bg-white p-5 rounded-xl shadow">
                <div class="flex items-center mb-4">
                   
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Performance Globale</h3>
                        <p class="text-sm text-gray-500">Indicateurs clés vs Cibles</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="chart5"></canvas>
                </div>
            </div>

            <!-- Graphique 6: Grouped Bar Chart -->
            <div class="bg-white p-5 rounded-xl shadow md:col-span-2 lg:col-span-1">
                <div class="flex items-center mb-4">
                   
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Comparaison Domaine</h3>
                        <p class="text-sm text-gray-500">Métriques par activité</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="chart6"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialiser tous les graphiques
    function initCharts() {
        // Données des graphiques
        const domainesData = @js($domainesStats);
        const centresData = @js($centresStats);
        const evolutionData = @js($evolutionStats);
        const statsData = @js($stats);
        const performanceData = @js($performanceStats);
        const comparisonData = @js($comparisonStats);

        // Graphique 1: Pie Chart
        const ctx1 = document.getElementById('chart1');
        if (ctx1) {
            new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: domainesData.map(d => d.label),
                    datasets: [{
                        data: domainesData.map(d => d.total),
                        backgroundColor: domainesData.map(d => d.color),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }

        // Graphique 2: Bar Chart (Top centres)
        const ctx2 = document.getElementById('chart2');
        if (ctx2) {
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: centresData.map(c => c.name),
                    datasets: [{
                        label: 'Bénéficiaires',
                        data: centresData.map(c => c.total),
                        backgroundColor: '#10B981',
                        borderColor: '#10B981',
                        borderWidth: 1
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

        // Graphique 3: Line Chart (Évolution)
        const ctx3 = document.getElementById('chart3');
        if (ctx3) {
            new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: evolutionData.map(e => e.annee),
                    datasets: [{
                        label: 'Bénéficiaires',
                        data: evolutionData.map(e => e.total_beneficiaires),
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4
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

        // Graphique 4: Doughnut Chart (Genre)
        const ctx4 = document.getElementById('chart4');
        if (ctx4) {
            new Chart(ctx4, {
                type: 'doughnut',
                data: {
                    labels: ['Hommes', 'Femmes'],
                    datasets: [{
                        data: [statsData.total_hommes, statsData.total_femmes],
                        backgroundColor: ['#3B82F6', '#EC4899'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }

        // Graphique 5: Radar Chart (Performance)
        const ctx5 = document.getElementById('chart5');
        if (ctx5) {
            new Chart(ctx5, {
                type: 'radar',
                data: performanceData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                stepSize: 20
                            }
                        }
                    },
                    elements: {
                        line: {
                            borderWidth: 3
                        }
                    }
                }
            });
        }

        // Graphique 6: Grouped Bar Chart (Comparaison)
        const ctx6 = document.getElementById('chart6');
        if (ctx6) {
            new Chart(ctx6, {
                type: 'bar',
                data: comparisonData,
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
    }

    // Initialiser quand la page est chargée
    document.addEventListener('DOMContentLoaded', initCharts);
</script>
