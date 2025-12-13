{{-- resources/views/livewire/impacts/partials/form-eps.blade.php --}}
<div class="space-y-6">
    <!-- Nombre d'handicaps traités -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre d'handicaps traités *</label>
            <input type="number" wire:model="donnees_specifiques.nombre_handicaps_traites" min="1" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('donnees_specifiques.nombre_handicaps_traites') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Heures médecin/année</label>
            <input type="number" wire:model="donnees_specifiques.heures_medecin_an" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <!-- Heures des professionnels -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Heures assistant social/année</label>
            <input type="number" wire:model="donnees_specifiques.heures_assistant_social_an" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Heures psychologue/année</label>
            <input type="number" wire:model="donnees_specifiques.heures_psychologue_an" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <!-- Totaux calculés -->
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <h4 class="text-md font-semibold text-blue-800 mb-3">Résumé des Heures d'Accompagnement</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-xl font-bold text-blue-600">
                    {{ ($donnees_specifiques['heures_medecin_an'] ?? 0) + 
                       ($donnees_specifiques['heures_assistant_social_an'] ?? 0) + 
                       ($donnees_specifiques['heures_psychologue_an'] ?? 0) }}h
                </div>
                <div class="text-sm text-blue-600">Total Heures/An</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-green-600">
                    @php
                        $totalHeures = ($donnees_specifiques['heures_medecin_an'] ?? 0) + 
                                     ($donnees_specifiques['heures_assistant_social_an'] ?? 0) + 
                                     ($donnees_specifiques['heures_psychologue_an'] ?? 0);
                        $totalBeneficiaires = $nombre_inscrits_hommes + $nombre_inscrits_femmes;
                        echo $totalBeneficiaires > 0 ? number_format($totalHeures / $totalBeneficiaires, 1) : 0;
                    @endphp h
                </div>
                <div class="text-sm text-green-600">Moyenne/Bénéficiaire</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-purple-600">
                    {{ $donnees_specifiques['nombre_handicaps_traites'] ?? 1 }}
                </div>
                <div class="text-sm text-purple-600">Handicaps Traités</div>
            </div>
        </div>
    </div>
</div>