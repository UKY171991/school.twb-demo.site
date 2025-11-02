<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportService;
use App\Mail\AutomatedReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GenerateScheduledReports extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reports:generate-scheduled';

    /**
     * The console command description.
     */
    protected $description = 'Generate and send scheduled reports';

    protected ReportService $reportService;

    /**
     * Create a new command instance.
     */
    public function __construct(ReportService $reportService)
    {
        parent::__construct();
        $this->reportService = $reportService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting scheduled report generation...');

        $schedules = $this->reportService->getScheduledReports();
        $processedCount = 0;

        foreach ($schedules as $schedule) {
            if (!$schedule['is_active']) {
                continue;
            }

            $nextRunAt = Carbon::parse($schedule['next_run_at']);
            
            if ($nextRunAt->isPast()) {
                $this->info("Processing report: {$schedule['report_type']}");
                
                try {
                    $this->generateAndSendReport($schedule);
                    $processedCount++;
                    
                    // Update next run date
                    $this->reportService->updateScheduledReport($schedule['id'], [
                        'next_run_at' => $this->calculateNextRunDate($schedule['frequency'])
                    ]);
                    
                } catch (\Exception $e) {
                    $this->error("Failed to generate report {$schedule['report_type']}: " . $e->getMessage());
                }
            }
        }

        $this->info("Processed {$processedCount} scheduled reports.");
        
        return 0;
    }

    /**
     * Generate and send a scheduled report
     */
    private function generateAndSendReport(array $schedule): void
    {
        // Calculate date range based on frequency
        $dateRange = $this->calculateDateRange($schedule['frequency']);
        
        // Generate report data
        $reportData = $this->generateReportData(
            $schedule['report_type'],
            $dateRange['start'],
            $dateRange['end'],
            $schedule['school_ids'] ?? null,
            $schedule['user_types'] ?? null
        );

        // Generate export file
        $fileName = $this->generateFileName($schedule['report_type'], $schedule['format'], $dateRange);
        $filePath = $this->generateExportFile($reportData, $fileName, $schedule['format'], $schedule['report_type']);

        // Send email to recipients
        foreach ($schedule['email_recipients'] as $recipient) {
            Mail::to($recipient)->send(new AutomatedReportMail(
                $schedule['report_type'],
                $reportData,
                $filePath,
                $fileName
            ));
        }

        $this->info("Report sent to " . count($schedule['email_recipients']) . " recipients");
    }

    /**
     * Generate report data based on type
     */
    private function generateReportData(string $reportType, Carbon $startDate, Carbon $endDate, ?array $schoolIds = null, ?array $userTypes = null): array
    {
        switch ($reportType) {
            case 'system_overview':
                return $this->reportService->getSystemOverviewData($startDate, $endDate, $schoolIds);
            
            case 'school_performance':
                return $this->reportService->getSchoolPerformanceData($startDate, $endDate, $schoolIds);
            
            case 'user_analytics':
                return $this->reportService->getUserAnalyticsData($startDate, $endDate, $schoolIds, $userTypes);
            
            case 'enrollment_trends':
                return $this->reportService->getEnrollmentTrendsData($startDate, $endDate, $schoolIds);
            
            default:
                throw new \InvalidArgumentException("Unknown report type: {$reportType}");
        }
    }

    /**
     * Calculate date range based on frequency
     */
    private function calculateDateRange(string $frequency): array
    {
        $endDate = Carbon::now();
        
        switch ($frequency) {
            case 'daily':
                $startDate = $endDate->copy()->subDay();
                break;
            case 'weekly':
                $startDate = $endDate->copy()->subWeek();
                break;
            case 'monthly':
                $startDate = $endDate->copy()->subMonth();
                break;
            case 'quarterly':
                $startDate = $endDate->copy()->subMonths(3);
                break;
            default:
                $startDate = $endDate->copy()->subMonth();
        }

        return [
            'start' => $startDate,
            'end' => $endDate
        ];
    }

    /**
     * Generate file name for export
     */
    private function generateFileName(string $reportType, string $format, array $dateRange): string
    {
        $startDate = $dateRange['start']->format('Y-m-d');
        $endDate = $dateRange['end']->format('Y-m-d');
        
        return "{$reportType}_{$startDate}_to_{$endDate}.{$format}";
    }

    /**
     * Generate export file and return storage path
     */
    private function generateExportFile(array $data, string $fileName, string $format, string $reportType): string
    {
        $content = $this->formatDataForExport($data, $format, $reportType);
        
        $filePath = "reports/scheduled/{$fileName}";
        Storage::put($filePath, $content);
        
        return $filePath;
    }

    /**
     * Format data for export
     */
    private function formatDataForExport(array $data, string $format, string $reportType): string
    {
        switch ($format) {
            case 'csv':
                return $this->arrayToCSV($data);
            
            case 'json':
                return json_encode($data, JSON_PRETTY_PRINT);
            
            case 'excel':
            case 'pdf':
            default:
                // For demo purposes, return CSV format
                // In production, use appropriate libraries
                return $this->arrayToCSV($data);
        }
    }

    /**
     * Convert array to CSV format
     */
    private function arrayToCSV(array $data): string
    {
        $csv = "Report Data Export\n";
        $csv .= "Generated: " . now()->format('Y-m-d H:i:s') . "\n\n";
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $csv .= strtoupper($key) . "\n";
                foreach ($value as $subKey => $subValue) {
                    if (is_array($subValue)) {
                        $csv .= $subKey . "," . implode(',', $subValue) . "\n";
                    } else {
                        $csv .= $subKey . "," . $subValue . "\n";
                    }
                }
                $csv .= "\n";
            } else {
                $csv .= $key . "," . $value . "\n";
            }
        }
        
        return $csv;
    }

    /**
     * Calculate next run date based on frequency
     */
    private function calculateNextRunDate(string $frequency): Carbon
    {
        switch ($frequency) {
            case 'daily':
                return Carbon::now()->addDay();
            case 'weekly':
                return Carbon::now()->addWeek();
            case 'monthly':
                return Carbon::now()->addMonth();
            case 'quarterly':
                return Carbon::now()->addMonths(3);
            default:
                return Carbon::now()->addDay();
        }
    }
}