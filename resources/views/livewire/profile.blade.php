<div class="min-h-screen bg-gray-50/50 py-12">
    <div class="container mx-auto px-4 max-w-6xl">
        
        <div class="mb-10 flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Mon Profil</h1>
                <p class="text-slate-500 mt-1">Gérez vos informations personnelles et préférences de sécurité.</p>
            </div>
            
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white border border-slate-200 text-slate-600 shadow-sm">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></div>
                    Compte Actif
                </span>
            </div>
        </div>

        <div class="space-y-4 mb-8">
            @if (session()->has('profile_message'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r shadow-sm flex justify-between items-center" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-emerald-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="ml-3 text-sm font-medium text-emerald-800">{{ session('profile_message') }}</p>
                    </div>
                </div>
            @endif

            @if (session()->has('password_message'))
                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r shadow-sm flex justify-between items-center" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-indigo-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <p class="ml-3 text-sm font-medium text-indigo-800">{{ session('password_message') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative group">
                    <div class="h-32 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
                    
                    <div class="px-6 pb-6 relative">
                        <div class="relative -mt-16 mb-4 flex justify-center">
                            <div class="relative group cursor-pointer w-32 h-32">
                                <label for="photo" class="block w-full h-full relative">
                                    @if(auth()->user()->photo)
                                        <img src="{{ Storage::url(auth()->user()->photo) }}" alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md group-hover:opacity-90 transition-opacity">
                                    @else
                                        <div class="w-32 h-32 rounded-full bg-slate-800 text-white flex items-center justify-center text-4xl font-bold border-4 border-white shadow-md">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    
                                    <div class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <input type="file" id="photo" wire:model="photo" class="hidden" accept="image/*">
                                </label>
                            </div>
                        </div>

                        <div class="text-center mb-6">
                            <h2 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h2>
                            <p class="text-sm text-slate-500 mb-2">{{ auth()->user()->email }}</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ auth()->user()->role === 'admin' ? 'bg-red-50 text-red-700 ring-1 ring-red-600/10' : '' }}
                                {{ auth()->user()->role === 'manager' ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/10' : '' }}
                                {{ auth()->user()->role === 'user' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/10' : '' }}">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>

                        <div class="border-t border-slate-100 pt-4 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500">Membre depuis</span>
                                <span class="font-medium text-slate-700">{{ auth()->user()->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500">Dernière connexion</span>
                                <span class="font-medium text-slate-700">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'Jamais' }}</span>
                            </div>
                        </div>

                        @error('photo') <span class="block text-center text-red-500 text-xs mt-2 bg-red-50 py-1 rounded">{{ $message }}</span> @enderror
                        
                        @if ($photo)
                            <div class="mt-4 text-center">
                                <span class="text-xs text-emerald-600 font-medium">Image prête à l'envoi</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Sécurité du compte
                    </h3>
                    <p class="text-sm text-slate-500 mb-5">Pour votre sécurité, nous recommandons de changer votre mot de passe périodiquement.</p>
                    <button wire:click="openPasswordModal" class="w-full group relative flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        Changer le mot de passe
                    </button>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-slate-800">Détails du compte</h3>
                        <button type="button" onclick="window.history.back()" class="text-slate-400 hover:text-slate-600 text-sm font-medium transition-colors">
                            &larr; Retour
                        </button>
                    </div>

                    <form wire:submit.prevent="saveProfile">
                        <div class="mb-8">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Informations Personnelles</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label for="name" class="text-sm font-medium text-slate-700">Nom complet</label>
                                    <input type="text" id="name" wire:model="name" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm transition duration-200 py-3 px-4">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="space-y-1">
                                    <label for="email" class="text-sm font-medium text-slate-700">Adresse email</label>
                                    <input type="email" id="email" wire:model="email" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm transition duration-200 py-3 px-4">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="space-y-1">
                                    <label for="phone" class="text-sm font-medium text-slate-700">Téléphone</label>
                                    <input type="text" id="phone" wire:model="phone" class="block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm transition duration-200 py-3 px-4">
                                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100 mb-8">

                        <div class="mb-8">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Informations Professionnelles</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label for="department" class="text-sm font-medium text-slate-700">Département</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <input type="text" id="department" wire:model="department" class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-10 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-3 px-4">
                                    </div>
                                    @error('department') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="space-y-1">
                                    <label for="position" class="text-sm font-medium text-slate-700">Poste actuel</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <input type="text" id="position" wire:model="position" class="block w-full rounded-xl border-slate-200 bg-slate-50 pl-10 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-3 px-4">
                                    </div>
                                    @error('position') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-6 border-t border-slate-100">
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl shadow-lg text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:scale-[1.02]">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($showPasswordModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity backdrop-blur-sm" wire:click="closePasswordModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Changer le mot de passe</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500 mb-4">Assurez-vous d'utiliser un mot de passe fort et unique.</p>
                                    
                                    <form wire:submit.prevent="updatePassword" class="space-y-4">
                                        <div>
                                            <input type="password" wire:model="current_password" placeholder="Mot de passe actuel" class="block w-full rounded-xl border-slate-300 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-4">
                                            @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <input type="password" wire:model="password" placeholder="Nouveau mot de passe" class="block w-full rounded-xl border-slate-300 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-4">
                                            @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <input type="password" wire:model="password_confirmation" placeholder="Confirmer le mot de passe" class="block w-full rounded-xl border-slate-300 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3 px-4">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="updatePassword" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Mettre à jour
                        </button>
                        <button type="button" wire:click="closePasswordModal" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>