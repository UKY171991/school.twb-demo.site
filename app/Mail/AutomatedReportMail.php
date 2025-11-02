<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class AutomatedReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reportType;
    public $reportData;
    public $filePath;
    public $fileName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $reportType, array $reportData, string $filePath, string $fileName)
    {
        $this->reportType = $reportType;
        $this->reportData = $reportData;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Automated Report: ' . ucwords(str_replace('_', ' ', $this->reportType));
        
        return $this->subject($subject)
                    ->view('emails.automated-report')
                    ->with([
                        'reportType' => $this->reportType,
                        'reportData' => $this->reportData,
                        'generatedAt' => now()->format('Y-m-d H:i:s')
                    ])
                    ->attach(Storage::path($this->filePath), [
                        'as' => $this->fileName,
                        'mime' => $this->getMimeType()
                    ]);
    }

    /**
     * Get MIME type based on file extension
     */
    private function getMimeType(): string
    {
        $extension = pathinfo($this->fileName, PATHINFO_EXTENSION);
        
        switch (strtolower($extension)) {
            case 'pdf':
                return 'application/pdf';
            case 'xlsx':
            case 'xls':
                return 'application/vnd.ms-excel';
            case 'csv':
                return 'text/csv';
            default:
                return 'application/octet-stream';
        }
    }
}