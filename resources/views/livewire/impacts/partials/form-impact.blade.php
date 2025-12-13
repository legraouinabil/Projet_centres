<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
            
            <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                        {{ $isEditing ? 'Modifier l\'Impact' : 'Nouvel Impact' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Renseignez les indicateurs de performance.</p>
                </div>
                <button wire:click="resetForm" type="button" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none transition-colors">
                    <span class="sr-only">Fermer</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="{{ $isEditing ? 'updateImpact' : 'createImpact' }}">
                <div class="max-h-[calc(100vh-200px)] overflow-y-auto px-6 py-6 bg-gray-50/50">
                    <div class="space-y-8">
                        
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                            <h4 class="flex items-center text-sm font-bold text-gray-900 uppercase tracking-wider mb-5">
                                <span class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 flex items-center justify-center mr-2 text-xs">1</span>
                                Contexte de l'intervention
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Centre <span class="text-red-500">*</span></label>
                                    <select wire:model.live="centre_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">Sélectionner...</option>
                                        @foreach($centres as $centre)
                                            <option value="{{ $centre->id }}">{{ $centre->denomination }}</option>
                                        @endforeach
                                    </select>
                                    @error('centre_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Domaine <span class="text-red-500">*</span></label>
                                    <select wire:model.live="domaine" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="formation_pro">Formation Pro</option>
                                        <option value="animation_culturelle_sportive">Animation Culturelle</option>
                                        <option value="handicap">Handicap</option>
                                        <option value="eps">EPS</option>
                                    </select>
                                </div>

                                <div class="md:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Année <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="annee" min="2000" max="2030" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    @error('annee') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        @if($domaine == 'formation_pro') Filière
                                        @elseif($domaine == 'animation_culturelle_sportive') Discipline
                                        @else Type d'handicap
                                        @endif <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="intitule_filiere_discipline" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Ex: Informatique, Football, Autisme...">
                                    @error('intitule_filiere_discipline') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                            <h4 class="flex items-center text-sm font-bold text-gray-900 uppercase tracking-wider mb-5">
                                <span class="bg-green-100 text-green-600 rounded-full w-6 h-6 flex items-center justify-center mr-2 text-xs">2</span>
                                Indicateurs Clés
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Hommes</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">H</span>
                                        </div>
                                        <input type="number" wire:model="nombre_inscrits_hommes" min="0" class="block w-full rounded-lg border-gray-300 pl-8 focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Femmes</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">F</span>
                                        </div>
                                        <input type="number" wire:model="nombre_inscrits_femmes" min="0" class="block w-full rounded-lg border-gray-300 pl-8 focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Abandons</label>
                                    <input type="number" wire:model="nombre_abandons" min="0" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-red-600">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Vol. Horaire / Pers.</label>
                                    <input type="number" wire:model="heures_par_beneficiaire" min="0" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6 pt-6 border-t border-gray-50">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Masse salariale</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" wire:model="masse_salariale" step="0.01" class="block w-full rounded-lg border-gray-300 pr-12 focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="0.00">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm">DH</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Charges globales</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" wire:model="charges_globales" step="0.01" class="block w-full rounded-lg border-gray-300 pr-12 focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="0.00">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm">DH</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50/50 p-5 rounded-xl border border-blue-100 border-dashed">
                             <h4 class="flex items-center text-sm font-bold text-blue-800 uppercase tracking-wider mb-5">
                                <span class="bg-blue-200 text-blue-700 rounded-full w-6 h-6 flex items-center justify-center mr-2 text-xs">3</span>
                                Données Spécifiques : <span class="ml-1 capitalize text-blue-600">{{ str_replace('_', ' ', $domaine) }}</span>
                            </h4>
                             @include('livewire.impacts.partials.donnees-specifiques.' . $domaine)
                        </div>

                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-2xl border-t border-gray-100">
                    <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:w-auto transition-colors">
                        {{ $isEditing ? 'Sauvegarder les modifications' : 'Créer l\'impact' }}
                    </button>
                    <button type="button" wire:click="resetForm" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>