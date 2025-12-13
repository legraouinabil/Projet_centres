<div class="container mx-auto px-4 py-6">
    <!-- En-tête -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête -->
       

        <!-- Contenu des rapports -->
        <div x-show="!$wire.loading" x-cloak class="space-y-6">
            <!-- Statistiques Globales -->
          

            <!-- Performance par Domaine -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Performance par Domaine d'Activité</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domaine</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activités</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bénéficiaires</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lauréats</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux Insertion</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($performanceStats as $domaine => $stats)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            bg-{{ $this->getDomaineColor($domaine) }}-100 text-{{ $this->getDomaineColor($domaine) }}-800">
                                            {{ ucfirst(str_replace('_', ' ', $domaine)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stats['total_activites'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($stats['total_beneficiaires'], 0, ',', ' ') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stats['total_laureats'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $stats['taux_insertion_moyen'] ? number_format($stats['taux_insertion_moyen'], 1) . '%' : 'N/A' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Performance des Centres -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Performance des Centres (Top 10)</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Centre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domaine</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bénéficiaires</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coût/Bénéficiaire</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Taux Insertion</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($centresPerformance as $centre)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $centre['denomination'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $centre['localisation'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            bg-{{ $this->getDomaineColor($centre['domaine']) }}-100 text-{{ $this->getDomaineColor($centre['domaine']) }}-800">
                                            {{ ucfirst(str_replace('_', ' ', $centre['domaine'])) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($centre['total_beneficiaires'], 0, ',', ' ') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($centre['budget_total'], 0, ',', ' ') }} DH
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold 
                                        {{ $centre['cout_par_beneficiaire'] < 1000 ? 'text-green-600' : ($centre['cout_par_beneficiaire'] < 5000 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($centre['cout_par_beneficiaire'], 0, ',', ' ') }} DH
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold 
                                        {{ $centre['taux_insertion_moyen'] > 70 ? 'text-green-600' : ($centre['taux_insertion_moyen'] > 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($centre['taux_insertion_moyen'], 1) }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Aperçu Financier -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Aperçu Financier</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Recettes Total</span>
                                <span class="text-sm font-semibold text-green-600">
                                    +{{ number_format($financialStats['total_recettes'] ?? 0, 0, ',', ' ') }} DH
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Dépenses Total</span>
                                <span class="text-sm font-semibold text-red-600">
                                    -{{ number_format($financialStats['total_depenses'] ?? 0, 0, ',', ' ') }} DH
                                </span>
                            </div>
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-medium text-gray-900">Solde Global</span>
                                    <span class="text-base font-bold text-{{ ($financialStats['total_recettes'] - $financialStats['total_depenses']) >= 0 ? 'green' : 'red' }}-600">
                                        {{ number_format(($financialStats['total_recettes'] - $financialStats['total_depenses']) ?? 0, 0, ',', ' ') }} DH
                                    </span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <span>Centres avec budget</span>
                                <span>{{ $financialStats['centres_avec_budget'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Indicateurs Clés</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Efficacité Budgétaire</span>
                                    <span>
                                        @php
                                            $efficacite = $financialStats['total_recettes'] > 0 ? 
                                                (($financialStats['total_recettes'] - $financialStats['total_depenses']) / $financialStats['total_recettes']) * 100 : 0;
                                        @endphp
                                        {{ number_format($efficacite, 1) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-{{ $efficacite >= 20 ? 'green' : ($efficacite >= 10 ? 'yellow' : 'red') }}-500" 
                                         style="width: {{ max(min($efficacite, 100), 0) }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Taux d'Occupation Moyen</span>
                                    <span>
                                        @php
                                            $tauxOccupation = $globalStats['total_beneficiaires'] > 0 ? 
                                                ($globalStats['total_beneficiaires'] / ($globalStats['total_centres'] * 100)) * 100 : 0;
                                        @endphp
                                        {{ number_format(min($tauxOccupation, 100), 1) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-{{ $tauxOccupation >= 80 ? 'green' : ($tauxOccupation >= 60 ? 'yellow' : 'red') }}-500" 
                                         style="width: {{ min($tauxOccupation, 100) }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Coût par Bénéficiaire Moyen</span>
                                    <span>
                                        @php
                                            $coutMoyen = $globalStats['total_beneficiaires'] > 0 ? 
                                                $globalStats['budget_total'] / $globalStats['total_beneficiaires'] : 0;
                                        @endphp
                                        {{ number_format($coutMoyen, 0, ',', ' ') }} DH
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
