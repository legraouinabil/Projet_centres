{{-- resources/views/livewire/system-settings.blade.php --}}
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">⚙️ System Settings</h1>
            <p class="text-gray-600 mt-2">Configure your application settings and preferences</p>
        </div>

        <!-- Success Message -->
        @if ($successMessage)
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ $successMessage }}</span>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg">
            <!-- Tabs -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    @foreach($tabs as $tabKey => $tabName)
                        <button
                            wire:click="$set('activeTab', '{{ $tabKey }}')"
                            class="{{ $activeTab === $tabKey ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        >
                            {{ $tabName }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Settings Form -->
            <form wire:submit.prevent="saveSettings">
                <div class="px-6 py-6">
                    @foreach($groupedSettings->get($activeTab, []) as $key => $setting)
                        <div class="mb-6">
                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 mb-2">
                            
                            </label>
                            
                            @if($setting['description'])
                                <p class="text-sm text-gray-500 mb-3">{{ $setting['description'] }}</p>
                            @endif

                            @if($setting['type'] === 'boolean')
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="{{ $key }}"
                                           wire:model="settings.{{ $key }}.value"
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="{{ $key }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $setting['value'] ? 'Enabled' : 'Disabled' }}
                                    </label>
                                </div>
                            @elseif($setting['type'] === 'text')
                                <textarea id="{{ $key }}"
                                          wire:model="settings.{{ $key }}.value"
                                          rows="3"
                                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            @elseif($setting['type'] === 'integer' || $setting['type'] === 'float')
                                <input type="number" 
                                       id="{{ $key }}"
                                       wire:model="settings.{{ $key }}.value"
                                       step="{{ $setting['type'] === 'float' ? '0.01' : '1' }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @else
                                <input type="text" 
                                       id="{{ $key }}"
                                       wire:model="settings.{{ $key }}.value"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @endif
                            
                            @error("settings.{$key}.value")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                    @if(count($groupedSettings->get($activeTab, [])) === 0)
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No settings found</h3>
                            <p class="mt-1 text-sm text-gray-500">There are no settings configured for this section.</p>
                        </div>
                    @endif
                </div>

                <!-- Form Actions -->
                @if(count($groupedSettings->get($activeTab, [])) > 0)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                        <button type="button" 
                                wire:click="resetToDefaults"
                                wire:confirm="Are you sure you want to reset all settings to defaults?"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Reset to Defaults
                        </button>
                        
                        <div class="space-x-3">
                            <button type="button" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Settings
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>