<div class="p-6 bg-white rounded-lg shadow-md">



    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Impacts Bénéficiaires</h1>
            <p class="mt-2 text-sm text-gray-600">
                Une liste de tous les impacts générés, incluant les centres, domaines et
                indicateurs clés.
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center gap-2">
            <button wire:click="$set('showForm', true)"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Ajouter un impact

            </button>

            <a href="{{ route('impacts.export.list', ['search' => $search, 'centre_id' => $centre_id, 'domaine' => $domaine, 'annee' => $annee]) }}" target="_blank"
                class="inline-flex items-center justify-center px-4 py-2 border border-red-600 shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none transition-all duration-200">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10v6h-4v4H7z" />
                </svg>
                Exporter (PDF)
            </a>
        </div>
    </div>

    <!-- Success Flash (session or Livewire public property) -->
    @if (session()->has('success') || !empty($successMessage))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false; Livewire.emit('clearSuccess'); }, 3000)"
            class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') ?? $successMessage }}
        </div>
    @endif

    <!-- Global Loading Overlay (Livewire requests + full page reloads) -->
    <div id="global-spinner" wire:loading.class.remove="hidden" class="hidden flex items-center justify-center fixed inset-0 z-50 bg-gray-900/60">
        <div class="flex flex-col items-center gap-2">
            <svg class="animate-spin h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span class="text-white text-sm">Chargement...</span>
        </div>
    </div>

    <!-- Statistiques (Style Cartes épurées) -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Total Impacts</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['total_impacts'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Bénéficiaires</div>
            <div class="text-2xl font-bold text-emerald-600">{{ $stats['total_beneficiaires'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Centres Actifs</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['total_centres'] }}</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Taux d'Abandon</div>
            <div class="text-2xl font-bold text-orange-600">{{ number_format($stats['taux_abandon_moyen'], 1) }}%
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Coût Moyen</div>
            <div class="text-2xl font-bold text-slate-800">{{ number_format($stats['cout_moyen_beneficiaire'], 2) }}
                <span class="text-xs text-slate-400 font-normal">DH</span>
            </div>
        </div>
    </div>

    <!-- Barre de Filtres (Design Unifié comme l'image) -->
    <div
        class="bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row items-center gap-2">
        <!-- Recherche -->
        <div class="relative flex-1 w-full md:w-auto group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-emerald-500 transition-colors" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" wire:model.live="search"
                class="block w-full pl-10 pr-3 py-2 border-none rounded-lg text-slate-600 placeholder-slate-400 focus:outline-none focus:ring-0 sm:text-sm bg-transparent"
                placeholder="Rechercher (Centre, Filière, Email...)">
        </div>

        <!-- Séparateur Vertical -->
        <div class="hidden md:block w-px h-8 bg-slate-200 mx-2"></div>

        <!-- Select Centre -->
        <div class="w-full md:w-48">
            <select wire:model.live="centre_id"
                class="w-full border-none text-slate-600 text-sm focus:ring-0 bg-transparent cursor-pointer hover:bg-slate-50 rounded-lg py-2">
                <option value="">Tous les Centres</option>
                @foreach ($centres as $centre)
                    <option value="{{ $centre->id }}">{{ $centre->denomination }}</option>
                @endforeach
            </select>
        </div>

        <!-- Séparateur Vertical -->
        <div class="hidden md:block w-px h-8 bg-slate-200 mx-2"></div>

        <!-- Select Domaine -->
        <div class="w-full md:w-48">
            <select wire:model.live="domaine"
                class="w-full border-none text-slate-600 text-sm focus:ring-0 bg-transparent cursor-pointer hover:bg-slate-50 rounded-lg py-2">
                <option value="">Tous les Domaines</option>
                <option value="formation_pro">Formation Pro</option>
                <option value="animation_culturelle_sportive">Animation Culturelle</option>
                <option value="handicap">Handicap</option>
                <option value="eps">EPS</option>
            </select>
        </div>

        <!-- Select Année -->
        <div class="hidden md:block w-px h-8 bg-slate-200 mx-2"></div>
        <div class="w-full md:w-32">
            <input type="number" wire:model.live="annee" min="2000" max="2030" placeholder="Année"
                class="w-full border-none text-slate-600 text-sm focus:ring-0 bg-transparent cursor-pointer hover:bg-slate-50 rounded-lg py-2">
        </div>

        <!-- Reset Button (X) -->
        @if ($this->hasActiveFilters)
            <button wire:click="resetFilters"
                class="p-2 ml-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                title="Réinitialiser">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50/50">
                <tr>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Centre / Année</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Filière & Domaine</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Bénéficiaires</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Statut / Indicateurs</th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Coût</th>
                    <th scope="col" class="relative px-6 py-4">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($impacts as $impact)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-150 group">
                        <!-- Colonne Centre avec "Avatar" -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div
                                        class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">
                                        {{ substr($impact->centre->denomination, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $impact->centre->denomination }}</div>
                                    <div class="text-sm text-slate-500">Année {{ $impact->annee }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Colonne Filière & Domaine -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900 font-medium">
                                {{ Str::limit($impact->intitule_filiere_discipline, 30) }}</div>
                            <div class="mt-1 flex items-center gap-2">
                                <!-- Badge Domaine -->
                                <span
                                    class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-medium rounded-full 
                                    {{ $impact->domaine == 'formation_pro' ? 'bg-blue-50 text-blue-700' : '' }}
                                    {{ $impact->domaine == 'animation_culturelle_sportive' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ $impact->domaine == 'handicap' ? 'bg-purple-50 text-purple-700' : '' }}
                                    {{ $impact->domaine == 'eps' ? 'bg-rose-50 text-rose-700' : '' }}">
                                    {{ $impact->domaine_label ?? 'Autre' }}
                                </span>
                                @if ($impact->partenaires->count() > 0)
                                    <span class="text-xs text-slate-400 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                                            </path>
                                        </svg>
                                        {{ $impact->partenaires->count() }} Partenaire(s)
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Colonne Bénéficiaires -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900">{{ $impact->total_inscrits }} Inscrits</div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                <span class="font-medium">{{ $impact->nombre_inscrits_hommes }}</span> H &bull;
                                <span class="font-medium">{{ $impact->nombre_inscrits_femmes }}</span> F
                            </div>
                        </td>

                        <!-- Colonne Indicateurs (Style Badge dot) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($impact->nombre_abandons > 0)
                                <div class="flex items-center mb-1">
                                    <span class="flex h-2 w-2 rounded-full bg-orange-500 mr-2"></span>
                                    <span class="text-xs text-slate-600">{{ $impact->nombre_abandons }}
                                        Abandons</span>
                                </div>
                            @else
                                <div class="flex items-center mb-1">
                                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 mr-2"></span>
                                    <span class="text-xs text-slate-600">Aucun abandon</span>
                                </div>
                            @endif

                            @if ($impact->formationPro && $impact->formationPro->nombre_laureats > 0)
                                <div class="text-xs text-slate-500 ml-4">
                                    {{ $impact->formationPro->nombre_laureats }} Lauréats
                                </div>
                            @endif
                        </td>

                        <!-- Colonne Coût -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            <div class="text-sm font-medium text-slate-900">
                                {{ number_format($impact->cout_revient_par_beneficiaire, 2) }} DH
                            </div>
                            <span class="text-xs italic text-slate-400">par bénéficiaire</span>
                        </td>

                        <!-- Colonne Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div
                                class="flex items-center justify-end space-x-3 opacity-80 group-hover:opacity-100 transition-opacity">

                                <!-- SHOW DETAILS (EYE ICON) -->
                                <button wire:click="showDetails({{ $impact->id }})"
                                    class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                    title="Voir les détails">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button> <!-- Edit -->
                                <!-- export removed: use top Exporter (PDF) button to export filtered list -->
                                <button wire:click="editImpact({{ $impact->id }})"
                                    class="text-emerald-600 hover:text-emerald-900 transition-colors"
                                    title="Modifier">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <!-- Partenaires -->
                                <button wire:click="showGestionPartenaires({{ $impact->id }})"
                                    class="text-amber-600 hover:text-amber-900 transition-colors" title="Partenaires">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </button>

                                <!-- Delete -->
                                <button wire:click="deleteImpact({{ $impact->id }})" wire:confirm="Êtes-vous sûr ?"
                                    class="text-red-400 hover:text-red-600 transition-colors" title="Supprimer">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-slate-50 rounded-full p-4 mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-slate-900">Aucun résultat</h3>
                                <p class="text-slate-500 mt-1 max-w-sm">Aucun impact ne correspond à vos critères
                                    de recherche. Essayez de réinitialiser les filtres.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($impacts->hasPages())
            <div class="bg-white border-t border-slate-200 px-6 py-4">
                {{ $impacts->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Formulaire Principal -->
    @if ($showForm)
        <!-- Main Modal Container -->
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 sm:p-6"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <!-- Backdrop with Blur -->
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" wire:click="resetForm">
            </div>

            <!-- Modal Content -->
            <div
                class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl transform transition-all flex flex-col max-h-[90vh]">

                <!-- HEADER -->
                <div
                    class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 tracking-tight">
                            {{ $isEditing ? 'Modifier l\'impact' : 'Nouvel impact bénéficiaire' }}
                        </h3>
                        <div class="flex items-center mt-1 text-sm text-slate-500">
                            <span>Formulaire de gestion</span>
                            @if ($domaine)
                                <span class="mx-2 text-slate-300">•</span>
                                <span
                                    class="font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full capitalize">
                                    {{ str_replace('_', ' ', $domaine) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <button wire:click="resetForm"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-2 rounded-full transition-all duration-200 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- SCROLLABLE FORM BODY -->
                <div class="p-6 overflow-y-auto custom-scrollbar">
                    <form wire:submit.prevent="{{ $isEditing ? 'updateImpact' : 'createImpact' }}">

                        <!-- SECTION 1: Informations Principales -->
                        <div class="bg-slate-50/50 p-5 rounded-xl border border-slate-100 mb-6">
                            <h4
                                class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">
                                Informations de base</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Centre -->
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Centre
                                        <span class="text-red-500">*</span></label>
                                    <select wire:model="centre_id" required
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-700 text-sm shadow-sm transition-shadow placeholder-slate-400">
                                        <option value="">Sélectionner un centre</option>
                                        @foreach ($centres as $centre)
                                            <option value="{{ $centre->id }}">{{ $centre->denomination }}</option>
                                        @endforeach
                                    </select>
                                    @error('centre_id')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Domaine -->
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Domaine
                                        d'activité <span class="text-red-500">*</span></label>
                                    <select wire:model.live="domaine" required
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-700 text-sm shadow-sm transition-shadow">
                                        <option value="">Sélectionner un domaine</option>
                                        <option value="formation_pro">Formation Professionnelle</option>
                                        <option value="animation_culturelle_sportive">Animation Culturelle & Sportive
                                        </option>
                                        <option value="handicap">Handicap</option>
                                        <option value="eps">EPS</option>
                                    </select>
                                    @error('domaine')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Année -->
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Année
                                        Budgétaire <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="annee" required min="2000" max="2030"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-700 text-sm shadow-sm transition-shadow">
                                    @error('annee')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Intitulé Dynamique -->
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                                        @if ($domaine == 'formation_pro')
                                            Intitulé de la filière <span class="text-red-500">*</span>
                                        @elseif($domaine == 'animation_culturelle_sportive')
                                            Intitulé de la discipline <span class="text-red-500">*</span>
                                        @elseif($domaine == 'handicap')
                                            Type de handicap traité <span class="text-red-500">*</span>
                                        @else
                                            Intitulé <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    <input type="text" wire:model="intitule_filiere_discipline" required
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-700 text-sm shadow-sm transition-shadow"
                                        placeholder="Ex: Informatique, Football, Autisme...">
                                    @error('intitule_filiere_discipline')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Données Chiffrées -->
                        <div class="mb-6">
                            <h4
                                class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                                Indicateurs & Bénéficiaires</h4>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <!-- Hommes -->
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Inscrits
                                        Hommes <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" wire:model="nombre_inscrits_hommes" required
                                            min="0"
                                            class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 text-sm shadow-sm">
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-blue-500 font-bold text-xs">H</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Femmes -->
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Inscrits
                                        Femmes <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" wire:model="nombre_inscrits_femmes" required
                                            min="0"
                                            class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 text-slate-700 text-sm shadow-sm">
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-pink-500 font-bold text-xs">F</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Heures -->
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                                        @if ($domaine == 'formation_pro')
                                            Volume Horaire
                                        @else
                                            Durée Séances
                                        @endif
                                        <span
                                            class="text-xs font-normal lowercase text-slate-400">(h/bénéficiaire)</span>
                                    </label>
                                    <input type="number" wire:model="heures_par_beneficiaire" required
                                        min="0"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-700 text-sm shadow-sm">
                                </div>

                                <!-- Abandons -->
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Abandons
                                        <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="nombre_abandons" required min="0"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-slate-700 text-sm shadow-sm">
                                </div>

                                <!-- Masse Salariale -->
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Masse
                                        salariale <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" wire:model="masse_salariale" required min="0"
                                            step="0.01"
                                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-700 text-sm shadow-sm">
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 text-xs">DH</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Charges Globales -->
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Charges
                                        globales <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="number" wire:model="charges_globales" required min="0"
                                            step="0.01"
                                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-700 text-sm shadow-sm">
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 text-xs">DH</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Données Spécifiques (Dynamic) -->
                        @if ($domaine)
                            <div class="bg-blue-50/30 p-5 rounded-xl border border-blue-100 mb-2">
                                <h4
                                    class="text-sm font-bold text-blue-800 uppercase tracking-wider mb-4 border-b border-blue-200 pb-2">
                                    Spécificités : <span
                                        class="capitalize">{{ str_replace('_', ' ', $domaine) }}</span>
                                </h4>

                                @if ($domaine == 'formation_pro')
                                    @include('livewire.impacts.partials.formation_pro', [
                                        'donnees' => $donnees_specifiques,
                                    ])
                                @elseif($domaine == 'animation_culturelle_sportive')
                                    @include('livewire.impacts.partials.animation_culturelle_sportive', [
                                        'donnees' => $donnees_specifiques,
                                    ])
                                @elseif($domaine == 'handicap')
                                    @include('livewire.impacts.partials.handicap', [
                                        'donnees' => $donnees_specifiques,
                                    ])
                                @elseif($domaine == 'eps')
                                    @include('livewire.impacts.partials.form-eps', [
                                        'donnees' => $donnees_specifiques,
                                    ])
                                @endif
                            </div>
                        @endif
                    </form>
                </div>

                <!-- FOOTER: Buttons -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" wire:click="resetForm"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg d-600 bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                        Annuler
                    </button>
                    <button type="button" wire:click="{{ $isEditing ? 'updateImpact' : 'createImpact' }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                        @if ($isEditing)
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Mettre à jour
                        @else
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Enregistrer l'impact
                        @endif
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Détails de l'Impact -->
    @if ($this->showDetailsModal && $this->selectedImpact)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 transition-opacity backdrop-blur-sm"
                wire:click="closeDetails"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl max-h-[90vh] flex flex-col">

                    <!-- Modal Header -->
                    <div
                        class="bg-white px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 z-10">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Détails de l'Impact</h3>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <span
                                    class="font-medium text-gray-900 mr-2">{{ $this->selectedImpact->centre->denomination }}</span>
                                <span class="text-gray-300 mx-2">•</span>
                                <span>{{ $this->selectedImpact->annee }}</span>
                                <span class="text-gray-300 mx-2">•</span>
                                @php
                                    $badgeColor = match ($this->selectedImpact->domaine) {
                                        'formation_pro' => 'text-blue-700 bg-blue-50',
                                        'animation_culturelle_sportive' => 'text-green-700 bg-green-50',
                                        'handicap' => 'text-purple-700 bg-purple-50',
                                        'eps' => 'text-orange-700 bg-orange-50',
                                        default => 'text-gray-700 bg-gray-50',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badgeColor }}">
                                    {{ $this->selectedImpact->domaine_label }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('impacts.export', $this->selectedImpact->id) }}" target="_blank"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none transition-colors">
                                <svg class="w-4 h-4 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h6l5 5v6a2 2 0 01-2 2H7a2 2 0 01-2-2V7z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 3v5a1 1 0 001 1h5" />
                                </svg>
                                Exporter PDF
                            </a>
                            <button wire:click="editImpact({{ $this->selectedImpact->id }})"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Modifier
                            </button>
                            <button wire:click="closeDetails"
                                class="text-gray-400 hover:text-gray-500 transition-colors p-1 rounded-full hover:bg-gray-100">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content (Scrollable) -->
                    <div class="px-6 py-6 overflow-y-auto custom-scrollbar">

                        <!-- KPI Cards -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                            <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100">
                                <dt class="text-xs font-medium text-blue-600 uppercase tracking-wide">Bénéficiaires
                                </dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">
                                    {{ $this->selectedImpact->total_inscrits }}</dd>
                                <dd class="mt-1 text-xs text-gray-500 flex items-center gap-2">
                                    <span class="flex items-center"><span
                                            class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-1"></span>{{ $this->selectedImpact->nombre_inscrits_hommes }}
                                        H</span>
                                    <span class="flex items-center"><span
                                            class="w-1.5 h-1.5 rounded-full bg-pink-400 mr-1"></span>{{ $this->selectedImpact->nombre_inscrits_femmes }}
                                        F</span>
                                </dd>
                            </div>
                            <div class="bg-green-50/50 rounded-xl p-4 border border-green-100">
                                <dt class="text-xs font-medium text-green-600 uppercase tracking-wide">Coût / Pers.
                                </dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">
                                    {{ number_format($this->selectedImpact->cout_revient_par_beneficiaire, 0) }} <span
                                        class="text-sm font-normal text-gray-500">DH</span></dd>
                                <dd class="mt-1 text-xs text-gray-500">
                                    {{ $this->selectedImpact->heures_par_beneficiaire }}h / bénéficiaire</dd>
                            </div>
                            <div class="bg-red-50/50 rounded-xl p-4 border border-red-100">
                                <dt class="text-xs font-medium text-red-600 uppercase tracking-wide">Abandons</dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">
                                    {{ $this->selectedImpact->nombre_abandons }}</dd>
                                <dd class="mt-1 text-xs text-gray-500">Taux:
                                    {{ number_format($this->selectedImpact->taux_abandon, 1) }}%</dd>
                            </div>
                            <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100">
                                <dt class="text-xs font-medium text-purple-600 uppercase tracking-wide">Partenaires
                                </dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">
                                    {{ $this->selectedImpact->partenaires->count() }}</dd>
                                <dd class="mt-1 text-xs text-gray-500 truncate"
                                    title="{{ $this->selectedImpact->intitule_filiere_discipline }}">
                                    {{ Str::limit($this->selectedImpact->intitule_filiere_discipline, 15) }}
                                </dd>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                            <!-- Left Column: Financials -->
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                    <h4 class="text-sm font-bold text-gray-900 uppercase">Données Financières</h4>
                                </div>
                                <div class="p-4 space-y-4">
                                    <div
                                        class="flex justify-between items-center pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                        <span class="text-sm text-gray-600">Masse Salariale</span>
                                        <span
                                            class="text-sm font-semibold text-gray-900">{{ number_format($this->selectedImpact->masse_salariale, 2) }}
                                            DH</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                        <span class="text-sm text-gray-600">Charges Globales</span>
                                        <span
                                            class="text-sm font-semibold text-gray-900">{{ number_format($this->selectedImpact->charges_globales, 2) }}
                                            DH</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                        <span class="text-sm text-gray-600">Coût unitaire</span>
                                        <span
                                            class="text-sm font-semibold text-gray-900">{{ number_format($this->selectedImpact->cout_revient_par_beneficiaire, 2) }}
                                            DH</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Domain Specific Details -->

                            <!-- FORMATION PRO -->
                            @if ($this->selectedImpact->domaine == 'formation_pro' && $this->selectedImpact->formationPro)
                                <div class="bg-white rounded-lg border border-blue-200 overflow-hidden">
                                    <div class="bg-blue-50 px-4 py-3 border-b border-blue-100">
                                        <h4 class="text-sm font-bold text-blue-800 uppercase">Détails Formation</h4>
                                    </div>
                                    <div class="p-4 grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs text-gray-500">Filières</div>
                                            <div class="font-semibold text-gray-900">
                                                {{ $this->selectedImpact->formationPro->nombre_filieres }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Lauréats</div>
                                            <div class="font-semibold text-gray-900">
                                                {{ $this->selectedImpact->formationPro->nombre_laureats }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Insertion</div>
                                            <div class="font-semibold text-green-600">
                                                {{ $this->selectedImpact->formationPro->taux_insertion_professionnelle }}%
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Salaire Moyen</div>
                                            <div class="font-semibold text-gray-900">
                                                {{ number_format($this->selectedImpact->formationPro->moyenne_premier_salaire, 0) }}
                                                DH</div>
                                        </div>
                                        <div
                                            class="col-span-2 pt-2 border-t border-gray-50 flex justify-between text-xs text-gray-500">
                                            <span>1ère année:
                                                <strong>{{ $this->selectedImpact->formationPro->nombre_inscrits_1ere_annee }}</strong></span>
                                            <span>2ème année:
                                                <strong>{{ $this->selectedImpact->formationPro->nombre_inscrits_2eme_annee }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- HANDICAP -->
                            @if ($this->selectedImpact->domaine == 'handicap' && $this->selectedImpact->handicap)
                                <div class="bg-white rounded-lg border border-purple-200 overflow-hidden">
                                    <div class="bg-purple-50 px-4 py-3 border-b border-purple-100">
                                        <h4 class="text-sm font-bold text-purple-800 uppercase">Suivi Médical & Social
                                        </h4>
                                    </div>
                                    <div class="p-4">
                                        <div class="mb-4">
                                            <div class="text-xs text-gray-500 mb-1">Handicaps Traités</div>
                                            <div class="font-bold text-gray-900 text-lg">
                                                {{ $this->selectedImpact->handicap->nombre_handicaps_traites }}</div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-sm">
                                            <div class="flex justify-between"><span
                                                    class="text-gray-600">Médecin</span> <span
                                                    class="font-medium">{{ $this->selectedImpact->handicap->heures_medecin_an }}h</span>
                                            </div>
                                            <div class="flex justify-between"><span class="text-gray-600">Assistant
                                                    Soc.</span> <span
                                                    class="font-medium">{{ $this->selectedImpact->handicap->heures_assistant_social_an }}h</span>
                                            </div>
                                            <div class="flex justify-between"><span
                                                    class="text-gray-600">Orthophoniste</span> <span
                                                    class="font-medium">{{ $this->selectedImpact->handicap->heures_orthophoniste_an }}h</span>
                                            </div>
                                            <div class="flex justify-between"><span class="text-gray-600">Kiné</span>
                                                <span
                                                    class="font-medium">{{ $this->selectedImpact->handicap->heures_kinesitherapie_an }}h</span>
                                            </div>
                                            <div class="flex justify-between"><span
                                                    class="text-gray-600">Psychologue</span> <span
                                                    class="font-medium">{{ $this->selectedImpact->handicap->heures_psychologue_an }}h</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- ANIMATION -->
                            @if ($this->selectedImpact->domaine == 'animation_culturelle_sportive' && $this->selectedImpact->animation)
                                <div class="bg-white rounded-lg border border-green-200 overflow-hidden">
                                    <div class="bg-green-50 px-4 py-3 border-b border-green-100">
                                        <h4 class="text-sm font-bold text-green-800 uppercase">Animation & Sport</h4>
                                    </div>
                                    <div class="p-4 grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs text-gray-500">Disciplines</div>
                                            <div class="font-semibold text-gray-900">
                                                {{ $this->selectedImpact->animation->nombre_disciplines }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Conventions</div>
                                            <div class="font-semibold text-gray-900">
                                                {{ $this->selectedImpact->animation->nombre_conventions }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Événements</div>
                                            <div class="font-semibold text-gray-900">
                                                {{ $this->selectedImpact->animation->nombre_evenements_organises }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Trophées</div>
                                            <div class="font-semibold text-yellow-600 flex items-center">
                                                {{ $this->selectedImpact->animation->nombre_trophees_gagnes }}
                                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div
                                            class="col-span-2 pt-2 border-t border-gray-50 flex justify-between text-xs">
                                            <span class="text-gray-600">Écoles:
                                                <strong>{{ $this->selectedImpact->animation->nombre_inscrits_ecoles }}</strong></span>
                                            <span class="text-gray-600">Particuliers:
                                                <strong>{{ $this->selectedImpact->animation->nombre_inscrits_particuliers }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- EPS -->
                            @if ($this->selectedImpact->domaine == 'eps' && $this->selectedImpact->eps)
                                <div class="bg-white rounded-lg border border-orange-200 overflow-hidden">
                                    <div class="bg-orange-50 px-4 py-3 border-b border-orange-100">
                                        <h4 class="text-sm font-bold text-orange-800 uppercase">Détails EPS</h4>
                                    </div>
                                    <div class="p-4">
                                        <div class="mb-3 flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Handicaps Traités</span>
                                            <span
                                                class="font-bold text-gray-900">{{ $this->selectedImpact->eps->nombre_handicaps_traites }}</span>
                                        </div>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between border-b border-gray-50 pb-1"><span
                                                    class="text-gray-500">Médecin</span> <span
                                                    class="font-medium">{{ $this->selectedImpact->eps->heures_medecin_an }}h</span>
                                            </div>
                                            <div class="flex justify-between border-b border-gray-50 pb-1"><span
                                                    class="text-gray-500">Assistant Soc.</span> <span
                                                    class="font-medium">{{ $this->selectedImpact->eps->heures_assistant_social_an }}h</span>
                                            </div>
                                            <div class="flex justify-between"><span
                                                    class="text-gray-500">Psychologue</span> <span
                                                    class="font-medium">{{ $this->selectedImpact->eps->heures_psychologue_an }}h</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <!-- Partenaires Section -->
                        @if ($this->selectedImpact->partenaires->count() > 0)
                            <div class="mt-8">
                                <h4 class="text-sm font-bold text-gray-900 uppercase mb-3">Partenaires Associés</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach ($this->selectedImpact->partenaires as $partenaire)
                                        <div
                                            class="bg-gray-50 rounded-lg p-3 border border-gray-200 flex items-center shadow-sm">
                                            <div
                                                class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold mr-3">
                                                {{ substr($partenaire->nom, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $partenaire->nom }}
                                                </div>
                                                @if ($partenaire->type)
                                                    <div class="text-xs text-gray-500">{{ $partenaire->type }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                        <div class="text-xs text-gray-400">
                            ID: #{{ $this->selectedImpact->id }} • Créé le
                            {{ $this->selectedImpact->created_at->format('d/m/Y') }}
                        </div>
                        <button wire:click="closeDetails"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Modal Gestion des Partenaires -->
    @if ($this->showPartenairesModal && $this->selectedImpact)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Gestion des Partenaires</h3>
                    <button wire:click="closePartenaires" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                    Centre: {{ $this->selectedImpact->centre->denomination }}<br>
                    Filière/Discipline: {{ $this->selectedImpact->intitule_filiere_discipline }}
                </p>
                <!-- Formulaire d'ajout de partenaire (placé avant la liste) -->
                <form wire:submit.prevent="savePartenaire" class="space-y-4 bg-gray-50 p-4 rounded-md mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Nom du partenaire *</label>
                            <input type="text" wire:model.defer="partenaire_nom"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('partenaire_nom') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Événements</label>
                            <input type="number" wire:model.defer="partenaire_evenements"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('partenaire_evenements') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Participations</label>
                            <input type="number" wire:model.defer="partenaire_participations"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('partenaire_participations') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Trophées</label>
                            <input type="number" wire:model.defer="partenaire_trophies"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @error('partenaire_trophies') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Ajouter
                        </button>
                        <button type="button" wire:click="resetPartenaireForm"
                                class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Réinitialiser
                        </button>
                    </div>
                </form>

                <!-- Liste des partenaires pour cet impact -->
                @if($this->selectedImpact->partenaires && $this->selectedImpact->partenaires->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nom</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Créé</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($this->selectedImpact->partenaires as $p)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $p->nom }}</td>
                                        <td class="px-4 py-3"><span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $p->type_label }}</span></td>
                                        <td class="px-4 py-3">{{ $p->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <button wire:click="deletePartenaire({{ $p->id }})" onclick="return confirm('Supprimer ce partenaire ?')"
                                                    class="text-red-600 hover:text-red-800 text-sm">Supprimer</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Aucun partenaire pour cet impact.</p>
                @endif
            </div>
        </div>
    @endif

      

    <script>
        (function () {
            var spinner = document.getElementById('global-spinner');
            // Show spinner on full page unload/navigation
            window.addEventListener('beforeunload', function () {
                if (spinner) spinner.classList.remove('hidden');
            });
            // Ensure spinner is hidden when arriving on the page (bfcache / back button)
            window.addEventListener('pageshow', function () {
                if (spinner) spinner.classList.add('hidden');
            });
            // Defensive hide on DOM ready
            document.addEventListener('DOMContentLoaded', function () {
                if (spinner) spinner.classList.add('hidden');
            });
        })();
    </script>
</div>
