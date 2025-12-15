<!-- resources/views/livewire/impacts/partials/gestion-partenaires.blade.php -->
<div x-data="{ show: @entangle('showPartenaires').live }" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:w-full sm:max-w-3xl">
            
            <div class="bg-white px-6 py-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Gestion des Partenaires</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500 bg-gray-50 p-2 rounded-full transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="px-6 py-6 bg-gray-50 max-h-[70vh] overflow-y-auto">
                
                <!-- Existing Partners List -->
                @if(count($partenaires) > 0)
                    <div class="space-y-4 mb-8">
                        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Partenaires Actuels</h4>
                        @foreach($partenaires as $index => $partenaire)
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex justify-between items-center group hover:border-indigo-300 transition-colors">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $partenaire['nom'] }}</p>
                                    <p class="text-xs text-gray-500 uppercase">{{ $partenaire['type'] }}</p>
                                    @if(isset($partenaire['contact_nom']))
                                        <p class="text-xs text-gray-400 mt-1">Contact: {{ $partenaire['contact_nom'] }}</p>
                                    @endif
                                </div>
                                <button wire:click="removePartenaire({{ $index }})" class="text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 italic bg-white rounded-lg border border-dashed border-gray-300 mb-8">
                        Aucun partenaire associé pour le moment.
                    </div>
                @endif

                <!-- Add New Partner Form -->
                <div class="bg-indigo-50 p-5 rounded-lg border border-indigo-100">
                    <h4 class="text-sm font-bold text-indigo-800 uppercase tracking-wide mb-4">Ajouter un nouveau partenaire</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-indigo-600 mb-1">Nom du Partenaire *</label>
                            <input type="text" wire:model="nouveau_partenaire.nom" class="w-full rounded-md border-indigo-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('nouveau_partenaire.nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-indigo-600 mb-1">Type *</label>
                            <select wire:model="nouveau_partenaire.type" class="w-full rounded-md border-indigo-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white">
                                <option value="institutionnel">Institutionnel</option>
                                <option value="entreprise">Entreprise</option>
                                <option value="ong">ONG</option>
                                <option value="association">Association</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-indigo-600 mb-1">Nom du Contact</label>
                            <input type="text" wire:model="nouveau_partenaire.contact_nom" class="w-full rounded-md border-indigo-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-indigo-600 mb-1">Téléphone</label>
                            <input type="text" wire:model="nouveau_partenaire.contact_telephone" class="w-full rounded-md border-indigo-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-indigo-600 mb-1">Email</label>
                            <input type="email" wire:model="nouveau_partenaire.contact_email" class="w-full rounded-md border-indigo-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button wire:click="addPartenaire" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Ajouter ce partenaire
                        </button>
                    </div>
                </div>

            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
                <button type="button" @click="show = false" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm transition-all">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>