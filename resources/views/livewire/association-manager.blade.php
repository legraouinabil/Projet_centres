<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

     <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Associations</h1>
            <p class="mt-2 text-sm text-gray-600 text-center sm:text-left">
                Vue d'ensemble des associations, leurs statistiques et leur état actuel.
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button wire:click="createAssociation"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouvelle Association
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div
            class="bg-white overflow-hidden rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-50 rounded-lg p-3">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Associations</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $totalAssociations }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white overflow-hidden rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-emerald-50 rounded-lg p-3">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Actives</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $activeAssociations }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white overflow-hidden rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-50 rounded-lg p-3">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Bénéficiaires</dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ number_format($totalBeneficiaires, 0, ',', ' ') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white overflow-hidden rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-50 rounded-lg p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Employés</dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ number_format($totalEmployes, 0, ',', ' ') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-lg bg-green-50 p-4 border-l-4 border-green-400 shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 font-medium">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-col lg:flex-row gap-4 justify-between items-end lg:items-center">

            <div class="w-full lg:w-1/4">
                <label for="search" class="sr-only">Recherche</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="search" wire:model.live="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition duration-150 ease-in-out"
                        placeholder="Rechercher une association...">
                </div>
            </div>

            <div class="w-full lg:w-3/4 flex flex-col sm:flex-row gap-3">
                <select wire:model.live="statusFilter"
                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                    <option value="">Tous les Statuts</option>
                    <option value="1">Actives</option>
                    <option value="0">Inactives</option>
                </select>

                <select wire:model.live="secteurFilter"
                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                    <option value="">Tous les Secteurs</option>
                    @foreach ($secteurs as $secteur)
                        <option value="{{ $secteur->id }}">{{ $secteur->nom_secteur_fr }}</option>
                    @endforeach
                </select>

                <select wire:model.live="districtFilter"
                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                    <option value="">Tous les Districts</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->nom_district_fr }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Association</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Coordonnées</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Secteur & District</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Chiffres Clés</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dossier</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($associations as $association)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div
                                            class="h-10 w-10 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm shadow-sm">
                                            {{ strtoupper(substr($association->nom_de_l_asso, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $association->nom_de_l_asso }}</div>
                                        @if ($association->nom_asso_ar)
                                            <div class="text-xs text-gray-500 font-arabic">
                                                {{ $association->nom_asso_ar }}</div>
                                        @endif
                                        <div class="text-xs text-gray-400 mt-0.5">Depuis:
                                            {{ $association->date_de_creation->format('Y') }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ $association->email }}
                                </div>
                                @if ($association->tel)
                                    <div class="text-xs text-gray-500 mt-1 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                        {{ $association->tel }}
                                    </div>
                                @endif
                                <div class="text-xs text-gray-400 mt-1 truncate max-w-xs"
                                    title="{{ $association->adresse }}">
                                    {{ $association->adresse }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $association->secteur->nom_secteur_fr ?? 'Non défini' }}
                                </span>
                                <div class="text-xs text-gray-500 mt-2">
                                    <span class="font-medium text-gray-700">District:</span>
                                    {{ $association->district->nom_district_fr ?? '-' }}
                                </div>
                              
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
                                    <div class="text-gray-500">Bénéficiaires:</div>
                                    <div class="font-semibold text-gray-900">{{ $association->nombreBeneficiaire }}</div>

                                    <div class="text-gray-500">Employés:</div>
                                    <div class="font-semibold text-gray-900">{{ $association->nombre_employes }}</div>

                                 

                                 
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($association->latestDossier)
                                    <div class="text-sm text-gray-900 font-semibold">
                                        {{ $association->latestDossier->title ??  'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $association->latestDossier->due_date ? \Carbon\Carbon::parse($association->latestDossier->due_date)->format('d/m/Y') : 'N/A' }}
                                    </div>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $association->latestDossier->status_badge }}">
                                            {{ $association->latestDossier->status_text }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Aucun dossier</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleAssociationStatus({{ $association->id }})"
                                    class="focus:outline-none transition-transform active:scale-95">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $association->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $association->is_active ? 'text-green-400' : 'text-red-400' }}"
                                            fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ $association->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </button>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-3">
                                    <button wire:click="editAssociation({{ $association->id }})"
                                        class="text-gray-400 hover:text-emerald-600 transition-colors"
                                        title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $association->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer cette association ?"
                                        class="text-gray-400 hover:text-red-600 transition-colors" title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center bg-white">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="h-14 w-14 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900">Aucune association trouvée</h3>
                                    <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">Aucune association ne
                                        correspond à vos critères de recherche. Essayez de modifier les filtres.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($associations->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $associations->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Création/Édition Association -->

    @if ($showAssociationModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="$set('showAssociationModal', false)" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl w-full">

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $associationId ? 'Modifier l\'Association' : 'Nouvelle Association' }}
                            </h3>
                            <button wire:click="$set('showAssociationModal', false)"
                                class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Fermer</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="max-h-[80vh] overflow-y-auto">
                        <form wire:submit.prevent="saveAssociation" class="px-4 py-5 sm:p-6">

                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-emerald-600 uppercase tracking-wide mb-4">
                                    Identité & Localisation</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                                    <div class="col-span-1 md:col-span-2 lg:col-span-1">
                                        <label for="nom_de_l_asso" class="block text-sm font-medium text-gray-700">Nom
                                            (FR) <span class="text-red-500">*</span></label>
                                        <input type="text" id="nom_de_l_asso" wire:model="nom_de_l_asso"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        @error('nom_de_l_asso')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-1 md:col-span-2 lg:col-span-1">
                                        <label for="nom_asso_ar" class="block text-sm font-medium text-gray-700">Nom
                                            (AR) <span class="text-red-500">*</span></label>
                                        <input type="text" id="nom_asso_ar" wire:model="nom_asso_ar"
                                            dir="rtl"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        @error('nom_asso_ar')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="date_de_creation"
                                            class="block text-sm font-medium text-gray-700">Date de création <span
                                                class="text-red-500">*</span></label>
                                        <input type="date" id="date_de_creation" wire:model="date_de_creation"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        @error('date_de_creation')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-span-1 md:col-span-2 lg:col-span-2">
                                        <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse
                                            complète <span class="text-red-500">*</span></label>
                                        <input type="text" id="adresse" wire:model="adresse"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        @error('adresse')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="jeagraphie" class="block text-sm font-medium text-gray-700">Ville
                                            / Région <span class="text-red-500">*</span></label>
                                        <input type="text" id="jeagraphie" wire:model="jeagraphie"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        @error('jeagraphie')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="border-gray-100 my-6">

                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-emerald-600 uppercase tracking-wide mb-4">Contact
                                    & Administratif</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

                                    <div class="lg:col-span-2">
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" id="email" wire:model="email"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        @error('email')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-2">
                                        <label for="tel"
                                            class="block text-sm font-medium text-gray-700">Téléphone <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" id="tel" wire:model="tel"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        @error('tel')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-2">
                                        <label for="site_web" class="block text-sm font-medium text-gray-700">Site
                                            web</label>
                                        <input type="url" id="site_web" wire:model="site_web"
                                            placeholder="https://"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    </div>

                                    <div class="lg:col-span-2">
                                        <label for="domaine_activite"
                                            class="block text-sm font-medium text-gray-700">Domaine d'activité</label>
                                        <input type="text" id="domaine_activite" wire:model="domaine_activite"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    </div>

                                    <div class="lg:col-span-2">
                                        <label for="secteur_id"
                                            class="block text-sm font-medium text-gray-700">Secteur <span
                                                class="text-red-500">*</span></label>
                                        <select id="secteur_id" wire:model="secteur_id"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                            <option value="">Sélectionnez...</option>
                                            @foreach ($secteurs as $secteur)
                                                <option value="{{ $secteur->id }}">{{ $secteur->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('secteur_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="lg:col-span-2">
                                        <label for="districts_id"
                                            class="block text-sm font-medium text-gray-700">District <span
                                                class="text-red-500">*</span></label>
                                        <select id="districts_id" wire:model="districts_id"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                            <option value="">Sélectionnez...</option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('districts_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    
                                </div>
                            </div>

                            <hr class="border-gray-100 my-6">

                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 mb-6">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">
                                    Informations Juridiques</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                    <div>
                                        <label for="statut_juridique"
                                            class="block text-sm font-medium text-gray-700">Statut juridique</label>
                                        <input type="text" id="statut_juridique" wire:model="statut_juridique"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="numero_agrement"
                                            class="block text-sm font-medium text-gray-700">N° d'agrément</label>
                                        <input type="text" id="numero_agrement" wire:model="numero_agrement"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="date_agrement"
                                            class="block text-sm font-medium text-gray-700">Date d'agrément</label>
                                        <input type="date" id="date_agrement" wire:model="date_agrement"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label for="budget_annuel" class="block text-sm font-medium text-gray-700">Budget
                                        annuel (DH)</label>
                                    <div class="relative mt-1 rounded-md shadow-sm">
                                        <input type="number" id="budget_annuel" wire:model="budget_annuel"
                                            step="0.01"
                                            class="block w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                            placeholder="0.00">
                                    </div>
                                </div>

                                <div>
                                    <label for="nombre_employes"
                                        class="block text-sm font-medium text-gray-700">Employés</label>
                                    <input type="number" id="nombre_employes" wire:model="nombre_employes"
                                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="nombreBeneficiaire"
                                        class="block text-sm font-medium text-gray-700">Bénéficiaires</label>
                                    <input type="number" id="nombreBeneficiaire" wire:model="nombreBeneficiaire"
                                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="remarque"
                                    class="block text-sm font-medium text-gray-700">Remarques</label>
                                <textarea id="remarque" wire:model="remarque" rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"></textarea>
                            </div>

                            <div class="flex items-center">
                                <button type="button" wire:click="$toggle('is_active')"
                                    class="{{ $is_active ? 'bg-emerald-600' : 'bg-gray-200' }} relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                                    role="switch" aria-checked="{{ $is_active }}">
                                    <span aria-hidden="true"
                                        class="{{ $is_active ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                                </button>
                                <span class="ml-3 text-sm text-gray-700">
                                    Association {{ $is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-gray-100">
                                <button type="button" wire:click="$set('showAssociationModal', false)"
                                    class="bg-white py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    Annuler
                                </button>
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    {{ $associationId ? 'Enregistrer les modifications' : 'Créer l\'association' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="$set('showDeleteModal', false)" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Supprimer l'association
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Êtes-vous sûr de vouloir supprimer cette association ? Cette action supprimera
                                        également toutes les données liées. Cette action est irréversible.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="deleteAssociation"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Supprimer
                        </button>
                        <button type="button" wire:click="$set('showDeleteModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
