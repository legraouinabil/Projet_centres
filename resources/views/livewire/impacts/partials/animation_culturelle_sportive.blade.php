{{-- resources/views/livewire/impacts/partials/form-animation.blade.php --}}
<div class="space-y-6">
    <!-- Nombre de disciplines -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de disciplines *</label>
            <input type="number" wire:model="donnees_specifiques.nombre_disciplines" min="1" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('donnees_specifiques.nombre_disciplines') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de conventions</label>
            <input type="number" wire:model="donnees_specifiques.nombre_conventions" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <!-- Inscrits par type -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre d'inscrits (écoles)</label>
            <input type="number" wire:model="donnees_specifiques.nombre_inscrits_ecoles" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre d'inscrits (particuliers)</label>
            <input type="number" wire:model="donnees_specifiques.nombre_inscrits_particuliers" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <!-- Activités et compétitions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Événements organisés/année</label>
            <input type="number" wire:model="donnees_specifiques.nombre_evenements_organises" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Participations compétitions</label>
            <input type="number" wire:model="donnees_specifiques.nombre_participations_competitions" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Trophées gagnés</label>
            <input type="number" wire:model="donnees_specifiques.nombre_trophees_gagnes" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <!-- Totaux calculés -->
    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
        <h4 class="text-md font-semibold text-green-800 mb-3">Résumé des Activités</h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-xl font-bold text-green-600">
                    {{ ($donnees_specifiques['nombre_inscrits_ecoles'] ?? 0) + 
                       ($donnees_specifiques['nombre_inscrits_particuliers'] ?? 0) }}
                </div>
                <div class="text-sm text-green-600">Total Inscrits</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-blue-600">
                    {{ $donnees_specifiques['nombre_evenements_organises'] ?? 0 }}
                </div>
                <div class="text-sm text-blue-600">Événements</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-purple-600">
                    {{ $donnees_specifiques['nombre_disciplines'] ?? 1 }}
                </div>
                <div class="text-sm text-purple-600">Disciplines</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-orange-600">
                    {{ $donnees_specifiques['nombre_trophees_gagnes'] ?? 0 }}
                </div>
                <div class="text-sm text-orange-600">Trophées</div>
            </div>
        </div>
    </div>
</div>