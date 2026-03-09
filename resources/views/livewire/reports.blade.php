{{-- resources/views/livewire/report-ressources-financieres.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6">
    <!-- Header -->
    
    <!-- CHART 1: Bar Chart - Annual Comparison -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">📊 Comparaison Annuelle</h3>
                <p class="text-sm text-gray-600">Recettes, dépenses et solde par année</p>
            </div>
            <div class="flex gap-3 mt-3 md:mt-0">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-emerald-500"></div>
                    <span class="text-sm text-gray-600">Recettes</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-rose-500"></div>
                    <span class="text-sm text-gray-600">Dépenses</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-blue-500"></div>
                    <span class="text-sm text-gray-600">Solde</span>
                </div>
            </div>
        </div>
        <div class="h-80">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <!-- CHART 2 & 3: Doughnut and Pie Charts Side by Side -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Doughnut Chart -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">🍩 Distribution des Budgets</h3>
                    <p class="text-sm text-gray-600">Répartition par catégorie de budget</p>
                </div>
                <div class="text-xs text-gray-500">
                    {{ array_sum($doughnutChart['datasets'][0]['data'] ?? []) }} entrées
                </div>
            </div>
            <div class="h-80 relative">
                <canvas id="doughnutChart"></canvas>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ array_sum($doughnutChart['datasets'][0]['data'] ?? []) }}
                        </div>
                        <div class="text-sm text-gray-600">Total</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">🥧 Performance des Centres</h3>
                    <p class="text-sm text-gray-600">Répartition par niveau d'efficacité</p>
                </div>
                <div class="text-xs text-gray-500">
                    {{ count($centresStats) }} centres
                </div>
            </div>
            <div class="h-80">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- CHART 4: Line Chart - Trend Analysis -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">📈 Analyse des Tendances</h3>
                <p class="text-sm text-gray-600">Évolution avec moyennes mobiles</p>
            </div>
            <div class="flex flex-wrap gap-3 mt-3 md:mt-0">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-0.5 bg-emerald-600"></div>
                    <span class="text-sm text-gray-600">Recettes</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-0.5 bg-rose-600"></div>
                    <span class="text-sm text-gray-600">Dépenses</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-0.5 bg-amber-600"></div>
                    <span class="text-sm text-gray-600">Taux</span>
                </div>
            </div>
        </div>
        <div class="h-80">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Centers Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">🏆 Top 10 des Centres</h3>
                        <p class="text-sm text-gray-600">Classement par recettes</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-medium bg-indigo-100 text-indigo-800 rounded-full">
                        {{ count($centresStats) }} centres
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Centre</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Recettes</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Solde</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse(array_slice($centresStats, 0, 10) as $centre)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $centre['centre_name'] }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ number_format($centre['total_depenses'], 0, ',', ' ') }} DH dépenses
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-bold text-gray-900">
                                    {{ number_format($centre['total_recettes'], 0, ',', ' ') }} DH
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $centre['solde'] >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }}" 
                                             style="width: {{ min(abs($centre['efficiency']), 100) }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium {{ $centre['solde'] >= 0 ? 'text-emerald-700' : 'text-rose-700' }} w-12 text-right">
                                        {{ number_format($centre['efficiency'], 1) }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                Aucune donnée disponible
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Annual Stats -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">📅 Évolution Annuelle</h3>
                <p class="text-sm text-gray-600">Performance par année budgétaire</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Année</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Recettes</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Ratio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($evolutionStats as $year)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $year['annee'] }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $year['nombre_centres'] ?? 0 }} centres
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-bold text-emerald-700">
                                    {{ number_format($year['total_recettes'], 0, ',', ' ') }} DH
                                </div>
                                <div class="text-sm text-rose-700">
                                    {{ number_format($year['total_depenses'], 0, ',', ' ') }} DH
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="text-sm font-medium {{ $year['taux_depenses'] <= 100 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $year['taux_depenses'] }}%
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        Solde: {{ number_format($year['solde'], 0, ',', ' ') }} DH
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                Aucune donnée annuelle disponible
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500">
        <p>Dashboard généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p class="mt-1">4 types de graphiques: Bar, Doughnut, Pie, Line</p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Helper function to format currency
        function formatCurrency(value) {
            if (value >= 1000000) {
                return (value / 1000000).toFixed(1) + 'M DH';
            }
            if (value >= 1000) {
                return (value / 1000).toFixed(0) + 'K DH';
            }
            return value + ' DH';
        }

        // CHART 1: Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: @json($barChart),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            },
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1f2937',
                        bodyColor: '#374151',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += formatCurrency(context.raw);
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif"
                            },
                            callback: formatCurrency
                        }
                    }
                }
            }
        });

        // CHART 2: Doughnut Chart
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        const doughnutChart = new Chart(doughnutCtx, {
            type: 'doughnut',
            data: @json($doughnutChart),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            },
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} entrées (${percentage}%)`;
                            },
                            afterLabel: function(context) {
                                const doughnutData = @json($doughnutChart);
                                const index = context.dataIndex;
                                const recettes = doughnutData.details?.total_recettes?.[index] || 0;
                                const solde = doughnutData.details?.total_solde?.[index] || 0;
                                return [
                                    `Recettes: ${formatCurrency(recettes)}`,
                                    `Solde: ${formatCurrency(solde)}`
                                ];
                            }
                        }
                    }
                }
            }
        });

        // CHART 3: Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: @json($pieChart),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            },
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} centres (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // CHART 4: Line Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: @json($lineChart),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 11,
                                family: "'Inter', sans-serif"
                            },
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1f2937',
                        bodyColor: '#374151',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label.includes('Taux')) {
                                    return `${label}: ${context.raw.toFixed(1)}%`;
                                }
                                return `${label}: ${formatCurrency(context.raw)}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif"
                            },
                            callback: formatCurrency
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif"
                            },
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                }
            }
        });

        // Auto-refresh data every 5 minutes
        setInterval(() => {
            Livewire.emit('refresh');
        }, 300000);
    });
</script>
