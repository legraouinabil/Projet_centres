<div class="min-h-screen bg-gray-50 py-8 font-sans">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ressources Humaines</h1>
                <p class="mt-1 text-sm text-gray-500">Une liste de tout le personnel de vos centres incluant leur nom, poste, et contrat.</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <button wire:click="create" 
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Ajouter un personnel
                </button>

                <a href="{{ route('ressources.export.rh', ['search' => $search ?? '', 'centre_id' => $centre_id ?? '', 'type_contrat' => $type_contrat ?? '']) }}" target="_blank"
                    class="inline-flex items-center justify-center px-4 py-2 border border-red-600 shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10v6h-4v4H7z" /></svg>
                    Exporter (PDF)
                </a>
            </div>
        </div>

        <!-- Filters Bar (Like the Image) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2 mb-6">
            <div class="flex flex-col sm:flex-row gap-2 items-center">
                <!-- Search Input -->
                <div class="relative flex-grow w-full sm:w-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                        class="block w-full pl-10 pr-3 py-2 border-none rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 sm:text-sm bg-transparent" 
                        placeholder="Rechercher (Nom, Email, Poste)...">
                </div>

                <!-- Filters Separator -->
                <div class="hidden sm:block h-6 w-px bg-gray-200 mx-2"></div>

                <!-- Center Filter -->
                <div class="w-full sm:w-auto">
                    <select class="block w-full pl-3 pr-10 py-2 text-base border-none focus:outline-none focus:ring-2 focus:ring-emerald-500/50 sm:text-sm rounded-lg bg-gray-50 text-gray-600 font-medium">
                        <option>Tous les Centres</option>
                        @foreach($centres as $centre)
                            <option value="{{ $centre->id }}">{{ $centre->denomination }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Contract Filter -->
                <div class="w-full sm:w-auto">
                    <select class="block w-full pl-3 pr-10 py-2 text-base border-none focus:outline-none focus:ring-2 focus:ring-emerald-500/50 sm:text-sm rounded-lg bg-gray-50 text-gray-600 font-medium">
                        <option>Tous les Contrats</option>
                        <option value="CDI">CDI</option>
                        <option value="CDD">CDD</option>
                        <option value="Anapec">Anapec</option>
                    </select>
                </div>

                <!-- Reset Button -->
                <button wire:click="$set('search', '')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <span class="sr-only">Réinitialiser</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Main Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employé</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Poste / Centre</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contrat</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Salaire</th>
                            <th scope="col" class="relative px-6 py-4"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($rhs as $rh)
                        <tr class="hover:bg-gray-50/80 transition-colors duration-150 group">
                            <!-- Employee Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 text-sm font-bold border border-emerald-200">
                                            {{ substr($rh->nom_prenom, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $rh->nom_prenom }}</div>
                                        <div class="text-xs text-gray-500">ID: #{{ $rh->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role / Center Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $rh->poste }}</div>
                                <div class="text-sm text-gray-500 flex items-center mt-0.5">
                                    <svg class="flex-shrink-0 mr-1.5 h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $rh->centre->denomination }}
                                </div>
                            </td>

                            <!-- Status/Contract Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ strtolower($rh->type_contrat) == 'cdi' ? 'bg-green-100 text-green-800' : 
                                      (strtolower($rh->type_contrat) == 'cdd' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                                    {{ $rh->type_contrat }}
                                </span>
                            </td>

                            <!-- Salary Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-600 font-mono">
                                {{ number_format($rh->salaire, 0, ',', ' ') }} DH
                            </td>

                            <!-- Actions Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $rh->id }})" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 p-1.5 rounded-md hover:bg-emerald-100 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="confirmDelete({{ $rh->id }}, (id) => @this.delete(id))" class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded-md hover:bg-red-100 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="mx-auto h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900">Aucun personnel trouvé</h3>
                                <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos filtres ou ajoutez un nouveau personnel.</p>
                                <button wire:click="create" class="mt-4 text-emerald-600 hover:text-emerald-700 font-medium text-sm">
                                    + Ajouter un personnel
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $rhs->links() }}
            </div>
        </div>
    </div>

    <!-- Modal (Modernized) -->
    <div x-data="{ show: @entangle('showModal').live }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:w-full sm:max-w-xl">
                
                <form wire:submit.prevent="save">
                    <div class="bg-white px-6 py-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">{{ $isEditing ? 'Modifier le Personnel' : 'Nouveau Personnel' }}</h3>
                            <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500 bg-gray-50 p-2 rounded-full hover:bg-gray-100 transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom & Prénom <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="nom_prenom" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm py-2.5">
                                @error('nom_prenom') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Poste <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="poste" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm py-2.5">
                                    @error('poste') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Centre <span class="text-red-500">*</span></label>
                                    <select wire:model="centre_id" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm py-2.5 bg-white">
                                        <option value="">Sélectionner...</option>
                                        @foreach($centres as $centre)
                                            <option value="{{ $centre->id }}">{{ $centre->denomination }}</option>
                                        @endforeach
                                    </select>
                                    @error('centre_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Salaire (DH) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" step="0.01" wire:model="salaire" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm py-2.5 pr-8">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400 text-xs font-bold">DH</div>
                                    </div>
                                    @error('salaire') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Contrat <span class="text-red-500">*</span></label>
                                    <select wire:model="type_contrat" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm py-2.5 bg-white">
                                        <option value="">Sélectionner...</option>
                                        <option value="CDI">CDI</option>
                                        <option value="CDD">CDD</option>
                                        <option value="Anapec">Anapec</option>
                                        <option value="Stage">Stage</option>
                                    </select>
                                    @error('type_contrat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-emerald-600 text-base font-bold text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:text-sm transition-all">
                            {{ $isEditing ? 'Enregistrer les modifications' : 'Créer le personnel' }}
                        </button>
                        <button type="button" @click="show = false" wire:click="resetForm" class="mt-3 w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:text-sm transition-all">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>