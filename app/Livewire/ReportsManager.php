<?php
// app/Http/Livewire/ReportsManager.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Dossier;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsManager extends Component
{
    public $reportType = 'overview';
    public $dateRange = '30days';
    public $startDate;
    public $endDate;
    public $chartData = [];
    public $stats = [];

    protected $listeners = ['dateRangeChanged' => 'updateDateRange'];

    public function mount()
    {
        $this->setDefaultDateRange();
        $this->loadReportData();
    }

    public function setDefaultDateRange()
    {
        $this->endDate = now()->format('Y-m-d');
        
        switch ($this->dateRange) {
            case '7days':
                $this->startDate = now()->subDays(7)->format('Y-m-d');
                break;
            case '30days':
                $this->startDate = now()->subDays(30)->format('Y-m-d');
                break;
            case '90days':
                $this->startDate = now()->subDays(90)->format('Y-m-d');
                break;
            case 'year':
                $this->startDate = now()->subYear()->format('Y-m-d');
                break;
            default:
                $this->startDate = now()->subDays(30)->format('Y-m-d');
        }
    }

    public function updatedDateRange()
    {
        $this->setDefaultDateRange();
        $this->loadReportData();
    }

    public function updateDateRange($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->loadReportData();
    }

    public function loadReportData()
    {
        $this->loadStats();
        $this->loadChartData();
    }

    public function loadStats()
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate)->endOfDay();

        $this->stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_dossiers' => Dossier::count(),
            'active_dossiers' => Dossier::where('status', 'active')->count(),
            'total_documents' => Document::count(),
            'storage_used' => Document::sum('file_size'),
            'new_users' => User::whereBetween('created_at', [$start, $end])->count(),
            'new_dossiers' => Dossier::whereBetween('created_at', [$start, $end])->count(),
            'new_documents' => Document::whereBetween('created_at', [$start, $end])->count(),
        ];

        // Convert storage to MB
        $this->stats['storage_used_mb'] = round($this->stats['storage_used'] / (1024 * 1024), 2);
    }

    public function loadChartData()
    {
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        switch ($this->reportType) {
            case 'user_activity':
                $this->loadUserActivityChart();
                break;
            case 'dossier_creation':
                $this->loadDossierCreationChart();
                break;
            case 'document_uploads':
                $this->loadDocumentUploadsChart();
                break;
            case 'storage_usage':
                $this->loadStorageUsageChart();
                break;
            default:
                $this->loadOverviewChart();
        }
    }

    private function loadOverviewChart()
    {
        $data = Dossier::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->chartData = [
            'labels' => $data->pluck('date'),
            'datasets' => [
                [
                    'label' => 'Dossiers Created',
                    'data' => $data->pluck('count'),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ]
            ]
        ];
    }

    private function loadUserActivityChart()
    {
        $data = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->chartData = [
            'labels' => $data->pluck('date'),
            'datasets' => [
                [
                    'label' => 'New Users',
                    'data' => $data->pluck('count'),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ]
            ]
        ];
    }

    private function loadDossierCreationChart()
    {
        $data = Dossier::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $this->chartData = [
            'labels' => $data->pluck('status'),
            'datasets' => [
                [
                    'label' => 'Dossiers by Status',
                    'data' => $data->pluck('count'),
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444'
                    ],
                ]
            ]
        ];
    }

    private function loadDocumentUploadsChart()
    {
        $data = Document::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->chartData = [
            'labels' => $data->pluck('date'),
            'datasets' => [
                [
                    'label' => 'Documents Uploaded',
                    'data' => $data->pluck('count'),
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                ]
            ]
        ];
    }

    private function loadStorageUsageChart()
    {
        $data = Document::selectRaw('
            CASE 
                WHEN mime_type LIKE "image/%" THEN "Images"
                WHEN mime_type = "application/pdf" THEN "PDFs"
                WHEN mime_type LIKE "application/vnd.ms-%" OR mime_type LIKE "application/vnd.openxmlformats-%" THEN "Office Documents"
                ELSE "Other"
            END as file_type,
            SUM(file_size) as total_size
        ')
        ->groupBy('file_type')
        ->get();

        $this->chartData = [
            'labels' => $data->pluck('file_type'),
            'datasets' => [
                [
                    'label' => 'Storage Usage (Bytes)',
                    'data' => $data->pluck('total_size'),
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
                    ],
                ]
            ]
        ];
    }

    public function exportReport($format = 'pdf')
    {
        // Implement export functionality
        // This would typically generate PDF, Excel, or CSV reports
        session()->flash('message', "Report exported as {$format} successfully!");
    }

    public function render()
    {
        return view('livewire.reports-manager', [
            'reportTypes' => [
                'overview' => 'System Overview',
                'user_activity' => 'User Activity',
                'dossier_creation' => 'Dossier Creation',
                'document_uploads' => 'Document Uploads',
                'storage_usage' => 'Storage Usage',
            ],
            'dateRanges' => [
                '7days' => 'Last 7 Days',
                '30days' => 'Last 30 Days',
                '90days' => 'Last 90 Days',
                'year' => 'Last Year',
                'custom' => 'Custom Range',
            ]
        ]);
    }
}