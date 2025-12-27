<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\File;

class CleanupOrphanedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:orphaned-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned image files that are no longer referenced in settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of orphaned files...');
        
        $uploadPath = public_path('uploads/school');
        
        if (!File::exists($uploadPath)) {
            $this->info('Upload directory does not exist.');
            return;
        }
        
        // Get all files in the upload directory
        $files = File::files($uploadPath);
        
        // Get all image settings from database
        $logoSetting = SystemSetting::where('key', 'school_logo')->first();
        $faviconSetting = SystemSetting::where('key', 'school_favicon')->first();
        
        $referencedFiles = [];
        
        if ($logoSetting && $logoSetting->value) {
            $referencedFiles[] = basename($logoSetting->value);
        }
        
        if ($faviconSetting && $faviconSetting->value) {
            $referencedFiles[] = basename($faviconSetting->value);
        }
        
        $deletedCount = 0;
        
        foreach ($files as $file) {
            $filename = $file->getFilename();
            
            // Skip if file is referenced in settings
            if (in_array($filename, $referencedFiles)) {
                continue;
            }
            
            // Delete orphaned file
            try {
                File::delete($file->getPathname());
                $this->info("Deleted orphaned file: {$filename}");
                $deletedCount++;
            } catch (\Exception $e) {
                $this->error("Failed to delete file {$filename}: " . $e->getMessage());
            }
        }
        
        $this->info("Cleanup completed. Deleted {$deletedCount} orphaned files.");
    }
}