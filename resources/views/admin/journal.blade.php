{{-- resources/views/auth/login-livewire.blade.php --}}
@extends('layout.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 transition-all hover:shadow-md">
            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-indigo-50 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Journal d'activité</h1>
                        <p class="text-sm text-gray-500 font-medium">Historique complet des actions effectuées sur la plateforme</p>
                    </div>
                </div>
                <div class="flex items-center shrink-0">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach($logs as $log)
                        <li>
                            <div class="relative pb-8">
                                {{-- Ligne verticale de la timeline --}}
                                @if (!$loop->last)
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif

                                <div class="relative flex items-start space-x-4">
                                    <!-- Avatar avec status dot -->
                                    <div class="relative">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 border-2 border-white flex items-center justify-center text-sm font-bold text-gray-600 shadow-sm">
                                            {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                        </div>
                                        <span class="absolute -bottom-0.5 -right-0.5 rounded-full bg-white p-0.5">
                                            @php
                                                $actionColor = match(true) {
                                                    str_contains(strtolower($log->action), 'create') => 'bg-green-500',
                                                    str_contains(strtolower($log->action), 'update') => 'bg-blue-500',
                                                    str_contains(strtolower($log->action), 'delete') => 'bg-red-500',
                                                    default => 'bg-gray-400'
                                                };
                                            @endphp
                                            <div class="h-2.5 w-2.5 rounded-full {{ $actionColor }}"></div>
                                        </span>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <div class="text-sm">
                                                <span class="font-bold text-gray-900">{{ $log->user->name ?? 'Système' }}</span>
                                            </div>
                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ $log->created_at->translatedFormat('d M Y à H:i') }} ({{ $log->created_at->diffForHumans() }})
                                            </p>
                                        </div>
                                        
                                        <div class="mt-2 text-sm text-gray-700">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="px-2 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200">
                                                    {{ $log->action }}
                                                </span>
                                                @if($log->subject_type)
                                                    <span class="text-gray-400 font-normal">sur</span>
                                                    <span class="text-indigo-600 font-semibold italic">
                                                        {{ class_basename($log->subject_type) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        @if(!empty($log->properties))
                                            <div class="mt-3 group">
                                                <div class="relative">
                                                    <div class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <span class="text-[10px] text-gray-400 bg-gray-800 px-2 py-1 rounded">JSON Data</span>
                                                    </div>
                                                    <pre class="text-[11px] leading-relaxed font-mono bg-[#1e293b] text-blue-300 p-4 rounded-lg overflow-x-auto shadow-inner max-h-64 scrollbar-thin scrollbar-thumb-gray-600"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</code></pre>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Pagination -->
                <div class="mt-10 border-t border-gray-100 pt-6">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Optionnel : Style pour une scrollbar plus fine dans le bloc JSON */
    .scrollbar-thin::-webkit-scrollbar { width: 6px; height: 6px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #475569; border-radius: 10px; }
</style>
@endsection