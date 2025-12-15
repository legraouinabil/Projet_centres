<?php
// app/Http/Livewire/SystemSettings.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;

class SystemSettings extends Component
{
    public $activeTab = 'general';
    public $settings = [];
    public $successMessage = '';

    protected $rules = [
        'settings.*.value' => 'required',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $settings = SystemSetting::all();
        foreach ($settings as $setting) {
            $this->settings[$setting->key] = [
                'value' => $setting->value,
                'type' => $setting->type,
                'group' => $setting->group,
                'description' => $setting->description,
            ];
        }

        // Set default values for missing settings
        $defaultSettings = $this->getDefaultSettings();
        foreach ($defaultSettings as $key => $default) {
            if (!isset($this->settings[$key])) {
                $this->settings[$key] = $default;
            }
        }
    }

    private function getDefaultSettings()
    {
        return [
            'app_name' => [
                'value' => config('app.name', 'Laravel'),
                'type' => 'string',
                'group' => 'general',
                'description' => 'The name of your application'
            ],
            'app_description' => [
                'value' => 'A Laravel-based document management system',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Brief description of your application'
            ],
            'app_timezone' => [
                'value' => config('app.timezone', 'UTC'),
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default timezone for the application'
            ],
            'app_locale' => [
                'value' => config('app.locale', 'en'),
                'type' => 'string',
                'group' => 'general',
                'description' => 'Default language/locale'
            ],
            'mail_from_address' => [
                'value' => 'noreply@example.com',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Default "from" email address'
            ],
            'mail_from_name' => [
                'value' => config('app.name', 'Laravel'),
                'type' => 'string',
                'group' => 'email',
                'description' => 'Default "from" name'
            ],
            'user_registration' => [
                'value' => false,
                'type' => 'boolean',
                'group' => 'users',
                'description' => 'Allow new user registration'
            ],
            'email_verification' => [
                'value' => false,
                'type' => 'boolean',
                'group' => 'users',
                'description' => 'Require email verification for new users'
            ],
            'max_file_size' => [
                'value' => 10,
                'type' => 'integer',
                'group' => 'files',
                'description' => 'Maximum file upload size in MB'
            ],
            'allowed_file_types' => [
                'value' => 'pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
                'type' => 'string',
                'group' => 'files',
                'description' => 'Comma-separated list of allowed file extensions'
            ],
            'backup_enabled' => [
                'value' => false,
                'type' => 'boolean',
                'group' => 'maintenance',
                'description' => 'Enable automatic backups'
            ],
            'backup_frequency' => [
                'value' => 'daily',
                'type' => 'string',
                'group' => 'maintenance',
                'description' => 'How often to run automatic backups'
            ],
            'maintenance_mode' => [
                'value' => false,
                'type' => 'boolean',
                'group' => 'maintenance',
                'description' => 'Put the application in maintenance mode'
            ],
            'session_lifetime' => [
                'value' => 120,
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Session lifetime in minutes'
            ],
            'login_attempts' => [
                'value' => 5,
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Maximum login attempts before lockout'
            ],
            'password_expiry' => [
                'value' => 90,
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Password expiry in days (0 for never)'
            ],
        ];
    }

    public function saveSettings()
    {
        $this->validate();

        foreach ($this->settings as $key => $data) {
            SystemSetting::setValue(
                $key,
                $data['value'],
                $data['type'],
                $data['group'],
                $data['description'],
                false
            );
        }

        $this->successMessage = 'Settings saved successfully!';
        $this->dispatchBrowserEvent('settings-saved');
    }

    public function resetToDefaults()
    {
        $defaultSettings = $this->getDefaultSettings();
        foreach ($defaultSettings as $key => $default) {
            $this->settings[$key] = $default;
        }
        $this->successMessage = 'Settings reset to defaults!';
    }

    public function render()
    {
        $groupedSettings = collect($this->settings)->groupBy('group');
        
        return view('livewire.system-settings', [
            'groupedSettings' => $groupedSettings,
            'tabs' => [
                'general' => 'General',
                'email' => 'Email',
                'users' => 'Users',
                'files' => 'Files',
                'security' => 'Security',
                'maintenance' => 'Maintenance',
            ]
        ]);
    }
}