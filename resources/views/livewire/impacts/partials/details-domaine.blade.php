@if($impact->domaine == 'formation_pro' && $impact->formationPro)
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        
        <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center border-b pb-3">
            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.207 5 7.5 5A3.5 3.5 0 004 8.5c0 .907.258 1.758.722 2.494m0 0a5.523 5.523 0 0110.556 0M12 18.755A8.252 8.252 0 017.5 20h9a8.252 8.252 0 01-4.5-1.245m0 0l-1.077-3.623C10.74 13.79 10 12.185 10 10.5a2.5 2.5 0 015 0c0 1.685-.74 3.29-1.423 4.632l-1.077 3.623z"/>
            </svg>
            Détails Spécifiques: Formation Professionnelle!!!!!!!!!!!!!!!!!!!!!
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            
            <div class="p-4 bg-blue-50 rounded-lg flex items-center justify-between border-l-4 border-blue-500">
                <div>
                    <div class="text-sm font-medium text-gray-500">Nb. de Filières</div>
                    <div class="text-2xl font-bold text-blue-700 mt-1">{{ $impact->formationPro->nombre_filieres }}</div>
                </div>
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>

            <div class="p-4 bg-purple-50 rounded-lg flex items-center justify-between border-l-4 border-purple-500">
                <div>
                    <div class="text-sm font-medium text-gray-500">Taux d'Insertion</div>
                    <div class="text-2xl font-bold text-purple-700 mt-1">{{ $impact->formationPro->taux_insertion_professionnelle }}%</div>
                </div>
                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            
            <div class="p-4 bg-orange-50 rounded-lg flex items-center justify-between border-l-4 border-orange-500">
                <div>
                    <div class="text-sm font-medium text-gray-500">Salaire Moyen (1er)</div>
                    <div class="text-2xl font-bold text-orange-700 mt-1">{{ number_format($impact->formationPro->moyenne_premier_salaire, 0) }} DH</div>
                </div>
                <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 15.536L17.808 11.85a1 1 0 000-1.414l-4.121-4.121a1 1 0 00-1.414 0l-4 4a1 1 0 000 1.414l3.687 3.687m4.243-4.243L15 17.5l-2.828 2.828a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l3.687-3.687"></path></svg>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t pt-4 mt-4">
            
            <div class="p-3 bg-green-50 rounded-lg text-center">
                <div class="text-xl font-bold text-green-700">{{ $impact->formationPro->nombre_laureats }}</div>
                <div class="text-xs text-gray-600 mt-1 uppercase tracking-wider">Lauréats</div>
            </div>
            
            <div class="p-3 bg-gray-50 rounded-lg text-center">
                <div class="text-xl font-bold text-gray-700">{{ $impact->formationPro->nombre_inscrits_1ere_annee }}</div>
                <div class="text-xs text-gray-600 mt-1 uppercase tracking-wider">Inscrits 1ère Année</div>
            </div>
            
            <div class="p-3 bg-gray-50 rounded-lg text-center">
                <div class="text-xl font-bold text-gray-700">{{ $impact->formationPro->nombre_inscrits_2eme_annee }}</div>
                <div class="text-xs text-gray-600 mt-1 uppercase tracking-wider">Inscrits 2ème Année</div>
            </div>

        </div>
    </div>
@elseif($impact->domaine == 'animation_culturelle_sportive' && $impact->animation)
    @elseif($impact->domaine == 'handicap' && $impact->handicap)
    @elseif($impact->domaine == 'eps' && $impact->eps)
    @endif