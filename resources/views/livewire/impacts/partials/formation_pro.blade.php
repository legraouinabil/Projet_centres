{{-- resources/views/livewire/impacts/partials/form-formation-pro.blade.php --}}
<div class="space-y-6">
    <!-- Nombre de filières -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de filières *</label>
            <input type="number" wire:model="donnees_specifiques.nombre_filieres" min="1" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('donnees_specifiques.nombre_filieres') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de lauréats</label>
            <input type="number" wire:model="donnees_specifiques.nombre_laureats" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <!-- Indicateurs de performance -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Taux d'insertion professionnelle (%)</label>
            <input type="number" wire:model="donnees_specifiques.taux_insertion_professionnelle" min="0" max="100" step="0.1"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Moyenne 1er salaire (DH)</label>
            <input type="number" wire:model="donnees_specifiques.moyenne_premier_salaire" min="0" step="0.01"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <!-- Inscrits par année -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre d'inscrits 1ère année</label>
            <input type="number" wire:model="donnees_specifiques.nombre_inscrits_1ere_annee" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre d'inscrits 2ème année</label>
            <input type="number" wire:model="donnees_specifiques.nombre_inscrits_2eme_annee" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <!-- Totaux calculés -->
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <h4 class="text-md font-semibold text-blue-800 mb-3">Résumé de la Formation</h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-xl font-bold text-blue-600">
                    {{ ($donnees_specifiques['nombre_inscrits_1ere_annee'] ?? 0) + 
                       ($donnees_specifiques['nombre_inscrits_2eme_annee'] ?? 0) }}
                </div>
                <div class="text-sm text-blue-600">Total Inscrits</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-green-600">
                    {{ $donnees_specifiques['nombre_laureats'] ?? 0 }}
                </div>
                <div class="text-sm text-green-600">Lauréats</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-purple-600">
                    {{ $donnees_specifiques['taux_insertion_professionnelle'] ?? 0 }}%
                </div>
                <div class="text-sm text-purple-600">Taux d'Insertion</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-orange-600">
                    {{ $donnees_specifiques['nombre_filieres'] ?? 1 }}
                </div>
                <div class="text-sm text-orange-600">Filières</div>
            </div>
        </div>
    </div>
</div>