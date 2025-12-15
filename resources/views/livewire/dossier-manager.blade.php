<div class="min-h-screen bg-gray-50 py-8 font-sans text-gray-900">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    @if($selectedDossier)
                        {{ $selectedDossier->title }}
                    @else
                        Dossiers
                    @endif
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    @if($selectedDossier)
                        Gestion des documents et détails du dossier.
                    @else
                        Une liste de tous vos dossiers incluant leur référence, association et statut.
                    @endif
                </p>
            </div>
            
            <div class="mt-4 sm:mt-0 flex gap-3">
                @if($selectedDossier)
                    <button wire:click="$set('selectedDossier', null)" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Retour
                    </button>
                    <button wire:click="addDocument" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajouter un document
                    </button>
                @else
                    <button wire:click="createDossier" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajouter un dossier
                    </button>
                @endif
            </div>
        </div>

        <!-- SUCCESS MESSAGE -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200 flex items-center shadow-sm">
                <svg class="h-5 w-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-medium text-green-800">{{ session('message') }}</span>
            </div>
        @endif

        @if(!$selectedDossier)
            <!-- FILTER BAR (Exact Match Style) -->
            <div class="bg-white border border-gray-200 rounded-lg p-2 mb-8 flex flex-col sm:flex-row items-center gap-4 shadow-sm">
                <!-- Search -->
                <div class="relative flex-grow w-full sm:w-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition duration-150 ease-in-out" 
                        placeholder="Rechercher (Titre, Réf)...">
                </div>

                <!-- Dropdown: Association -->
                <div class="w-full sm:w-auto">
                    <select wire:model.live="association_id" class="block w-full pl-3 pr-10 py-2 text-base border-none focus:outline-none focus:ring-0 sm:text-sm rounded-md text-gray-900 font-medium hover:bg-gray-50 cursor-pointer">
                        <option value="">Toutes les Associations</option>
                        @foreach($associations as $asso)
                            <option value="{{ $asso->id }}">{{ Str::limit($asso->nom_de_l_asso, 20) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown: Statut (Border Left Separator) -->
                <div class="w-full sm:w-auto border-l border-gray-200 pl-4">
                    <select wire:model.live="status" class="block w-full pl-3 pr-10 py-2 text-base border-none focus:outline-none focus:ring-0 sm:text-sm rounded-md text-gray-900 font-medium hover:bg-gray-50 cursor-pointer">
                        <option value="">Tous les Statuts</option>
                        <option value="active">Actif</option>
                        <option value="draft">Brouillon</option>
                        <option value="completed">Terminé</option>
                        <option value="archived">Archivé</option>
                    </select>
                </div>

                <!-- Reset Button -->
                <button wire:click="$set('search', ''); $set('association_id', ''); $set('status', '')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors border border-gray-200 ml-auto">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- TABLE -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dossier</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Association</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Réf / Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($dossiers as $dossier)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    
                                    <!-- Dossier Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-lg">
                                                    {{ strtoupper(substr($dossier->title, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $dossier->title }}</div>
                                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                    {{ $dossier->documents->count() }} doc(s)
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Association Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">{{ $dossier->association->nom_de_l_asso ?? 'Non définie' }}</div>
                                    </td>

                                    <!-- Ref / Date Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $dossier->reference }}</div>
                                        <div class="text-xs text-gray-500 italic">{{ $dossier->due_date ? $dossier->due_date->format('d/m/Y') : '-' }}</div>
                                    </td>

                                    <!-- Statut Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusConfig = [
                                                'active' => ['class' => 'bg-emerald-100 text-emerald-800', 'label' => 'Actif'],
                                                'draft' => ['class' => 'bg-gray-100 text-gray-800', 'label' => 'Brouillon'],
                                                'completed' => ['class' => 'bg-blue-100 text-blue-800', 'label' => 'Terminé'],
                                                'archived' => ['class' => 'bg-red-100 text-red-800', 'label' => 'Archivé'],
                                            ];
                                            $config = $statusConfig[$dossier->status] ?? $statusConfig['draft'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                                            {{ $config['label'] }}
                                        </span>
                                    </td>

                                    <!-- Actions Column -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-3">
                                            <!-- View Button -->
                                            <button wire:click="selectDossier({{ $dossier->id }})" class="text-gray-400 hover:text-blue-600 transition-colors" title="Voir les documents">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                            <!-- Edit Button (Green) -->
                                            <button wire:click="editDossier({{ $dossier->id }})" class="text-emerald-600 hover:text-emerald-900 transition-colors" title="Modifier">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <!-- Delete Button (Red) -->
                                            <button wire:click="deleteDossier({{ $dossier->id }})" wire:confirm="Supprimer ce dossier ?" class="text-red-500 hover:text-red-700 transition-colors" title="Supprimer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500 italic">
                                        Aucun dossier trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- DETAIL VIEW: DOCUMENTS (Keeping consistent style) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in">
                <!-- Dossier Info -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 sticky top-24">
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-xl mr-3">
                                {{ strtoupper(substr($selectedDossier->title, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $selectedDossier->title }}</h3>
                                <p class="text-xs text-gray-500">{{ $selectedDossier->reference }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3 text-sm border-t border-gray-100 pt-4">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Statut</span>
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($selectedDossier->status) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Association</span>
                                <span class="font-medium text-gray-900">{{ $selectedDossier->association->nom_de_l_asso ?? '-' }}</span>
                            </div>
                            @if($selectedDossier->description)
                                <div class="pt-2">
                                    <span class="text-gray-500 block mb-1">Description</span>
                                    <p class="text-gray-700 bg-gray-50 p-2 rounded text-xs">{{ $selectedDossier->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Documents List -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Documents ({{ count($documents) }})</h3>
                        </div>
                        <ul class="divide-y divide-gray-200">
                            @forelse($documents as $doc)
                                <li class="px-6 py-4 hover:bg-gray-50 flex items-center justify-between group transition-colors">
                                    <div class="flex items-center min-w-0">
                                        <div class="flex-shrink-0 h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div class="ml-4 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $doc->name }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format($doc->file_size / 1024, 2) }} KB • {{ $doc->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                        <button wire:click="downloadDocument({{ $doc->id }})" class="p-1.5 rounded-md text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </button>
                                        <button wire:click="deleteDocument({{ $doc->id }})" wire:confirm="Supprimer ce document ?" class="p-1.5 rounded-md text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="px-6 py-12 text-center text-sm text-gray-500 italic">Aucun document.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- DOSSIER MODAL -->
    <div x-data="{ show: @entangle('showDossierModal').live }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <form wire:submit.prevent="saveDossier">
                    <div class="bg-white px-6 py-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-900">{{ $dossierId ? 'Modifier le Dossier' : 'Nouveau Dossier' }}</h3>
                            <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Association <span class="text-red-500">*</span></label>
                                <select wire:model="association_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                                    <option value="">Sélectionner une association</option>
                                    @foreach($associations as $assoc)
                                        <option value="{{ $assoc->id }}">{{ $assoc->nom_de_l_asso }}</option>
                                    @endforeach
                                </select>
                                @error('association_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="title" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Référence <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="reference" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                                    @error('reference') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <input type="date" wire:model="due_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                <select wire:model="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                                    <option value="draft">Brouillon</option>
                                    <option value="active">Actif</option>
                                    <option value="completed">Terminé</option>
                                    <option value="archived">Archivé</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea wire:model="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:text-sm">
                            {{ $dossierId ? 'Mettre à jour' : 'Enregistrer' }}
                        </button>
                        <button type="button" @click="show = false" class="mt-3 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DOCUMENT MODAL -->
    <div x-data="{ show: @entangle('showDocumentModal').live }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

            <div x-show="show" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <form wire:submit.prevent="saveDocument">
                    <div class="bg-white px-6 py-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Ajouter un Document</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="documentName" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2">
                                @error('documentName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fichier <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="documentFile" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                <div wire:loading wire:target="documentFile" class="text-xs text-emerald-600 mt-1">Chargement...</div>
                                @error('documentFile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea wire:model="documentDescription" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse">
                        <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:text-sm">
                            Téléverser
                        </button>
                        <button type="button" @click="show = false" class="mt-3 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>