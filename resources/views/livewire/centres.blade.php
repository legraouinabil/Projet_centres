<div class="min-h-screen bg-white py-8 font-sans text-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des Centres</h1>
                <p class="mt-1 text-sm text-gray-500">Une liste de tous les centres d'intervention, incluant leur domaine, localisation et superficie.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button wire:click="create" 
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-sm transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Ajouter un centre
                </button>
            </div>
        </div>

        <!-- Filter Bar (Exact Match Style) -->
        <div class="bg-white border border-gray-200 rounded-lg p-1.5 mb-8 flex flex-col sm:flex-row items-center gap-2 shadow-sm">
            <!-- Search -->
            <div class="relative flex-grow w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" 
                    class="block w-full pl-10 pr-3 py-2 border-none rounded-md text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 sm:text-sm bg-transparent" 
                    placeholder="Rechercher (Nom, Ville)...">
            </div>

            <!-- Separator -->
            <div class="hidden sm:block h-6 w-px bg-gray-200"></div>

            <!-- Dropdown: Domaine -->
            <div class="w-full sm:w-auto relative group">
                <select class="appearance-none bg-transparent block w-full pl-3 pr-8 py-2 text-sm font-medium text-gray-700 border-none focus:outline-none focus:ring-0 cursor-pointer hover:bg-gray-50 rounded-md">
                    <option value="">Tous les Domaines</option>
                    <option value="formation_professionnelle">Formation Pro</option>
                    <option value="animation_culturelle_sportive">Animation Culturelle</option>
                    <option value="handicap">Handicap</option>
                    <option value="eps">EPS</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Separator -->
            <div class="hidden sm:block h-6 w-px bg-gray-200"></div>

            <!-- Dropdown: Sort (Optional) -->
            <div class="w-full sm:w-auto relative group">
                <select class="appearance-none bg-transparent block w-full pl-3 pr-8 py-2 text-sm font-medium text-gray-700 border-none focus:outline-none focus:ring-0 cursor-pointer hover:bg-gray-50 rounded-md">
                    <option value="asc">Nom (A-Z)</option>
                    <option value="desc">Nom (Z-A)</option>
                    <option value="newest">Plus récent</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Reset Button -->
            <button wire:click="$set('search', '')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors border-l border-gray-200 ml-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Table -->
        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Centre</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Domaine</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Localisation</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Superficie</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($centres as $centre)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            
                            <!-- Centre Name & Goals -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-sm border border-emerald-200">
                                            {{ substr($centre->denomination, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $centre->denomination }}</div>
                                        <div class="text-xs text-gray-500 max-w-xs truncate" title="{{ $centre->objectifs }}">
                                            {{ Str::limit($centre->objectifs, 40) }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Domaine (Badge Style) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($centre->domaine_intervention == 'formation_professionnelle') bg-emerald-100 text-emerald-800
                                    @elseif($centre->domaine_intervention == 'animation_culturelle_sportive') bg-blue-100 text-blue-800
                                    @elseif($centre->domaine_intervention == 'handicap') bg-purple-100 text-purple-800
                                    @else bg-orange-100 text-orange-800 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                                    {{ ucfirst(str_replace('_', ' ', $centre->domaine_intervention)) }}
                                </span>
                            </td>

                            <!-- Localisation -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $centre->localisation }}
                            </td>

                            <!-- Superficie -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                {{ number_format($centre->superficie, 0, ',', ' ') }} m²
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <button wire:click="edit({{ $centre->id }})" class="text-emerald-600 hover:text-emerald-900 transition-colors p-1 rounded hover:bg-emerald-50" title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button onclick="confirmDelete({{ $centre->id }}, (id) => @this.delete(id))" class="text-red-500 hover:text-red-700 transition-colors p-1 rounded hover:bg-red-50" title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="text-gray-500 text-sm italic">Aucun centre trouvé.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-white">
                {{ $centres->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL (Matching Style) -->
    <div x-data="{ show: $wire.entangle('showModal').live }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                
                <form wire:submit.prevent="save">
                    <div class="bg-white px-6 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900">{{ $isEditing ? 'Modifier le Centre' : 'Nouveau Centre' }}</h3>
                            <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-6 bg-gray-50 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            
                            <!-- Denomination -->
                            <div class="sm:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dénomination <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="denomination" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                                @error('denomination') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Domaine -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Domaine <span class="text-red-500">*</span></label>
                                <select wire:model="domaine_intervention" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2 bg-white">
                                    <option value="">Sélectionner...</option>
                                    <option value="formation_professionnelle">Formation Pro</option>
                                    <option value="animation_culturelle_sportive">Animation Culturelle</option>
                                    <option value="handicap">Handicap</option>
                                    <option value="eps">EPS</option>
                                </select>
                                @error('domaine_intervention') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <!-- Localisation -->
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Localisation</label>
                                <input type="text" wire:model="localisation" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                            </div>

                            <!-- Superficie -->
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Superficie (m²)</label>
                                <input type="number" wire:model="superficie" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                            </div>

                            <!-- Coût Construction -->
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Coût Construction (DH)</label>
                                <input type="number" step="0.01" wire:model="cout_construction" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                            </div>

                            <!-- Coût Equipement -->
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Coût Équipement (DH)</label>
                                <input type="number" step="0.01" wire:model="cout_equipement" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                            </div>

                            <!-- Objectifs -->
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Objectifs</label>
                                <textarea wire:model="objectifs" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse border-t border-gray-200">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ $isEditing ? 'Enregistrer' : 'Créer' }}
                        </button>
                        <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>