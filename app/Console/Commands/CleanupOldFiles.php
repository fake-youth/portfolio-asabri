<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\FundFactSheet;
use App\Models\LaporanMingguan;
use App\Models\LaporanBulanan;
use App\Models\LaporanTahunan;

class CleanupOldFiles extends Command
{
    /**
     * Command signature
     */
    protected $signature = 'asabri:cleanup-files
                          {--days=180 : Delete files older than X days}
                          {--dry-run : Preview what will be deleted}';

    /**
     * Command description
     */
    protected $description = 'Cleanup old files and orphaned storage files';

    /**
     * Execute the console command
     */
    public function handle()
    {
        $days = $this->option('days');
        $dryRun = $this->option('dry-run');

        $this->info("Starting cleanup process...");
        $this->info("Files older than {$days} days will be processed.");

        if ($dryRun) {
            $this->warn("DRY RUN MODE - No files will be deleted");
        }

        // Cleanup database records
        $date = now()->subDays($days);

        $models = [
            FundFactSheet::class => 'Fund Fact Sheets',
            LaporanMingguan::class => 'Laporan Mingguan',
            LaporanBulanan::class => 'Laporan Bulanan',
            LaporanTahunan::class => 'Laporan Tahunan',
        ];

        $totalDeleted = 0;

        foreach ($models as $model => $name) {
            $old = $model::where('created_at', '<', $date)->get();

            if ($old->count() > 0) {
                $this->info("\n{$name}: Found {$old->count()} old records");

                foreach ($old as $record) {
                    $this->line("  - {$record->judul} ({$record->created_at->format('d/m/Y')})");

                    if (!$dryRun) {
                        // Delete file
                        Storage::disk('public')->delete($record->file_path);
                        // Delete record
                        $record->delete();
                        $totalDeleted++;
                    }
                }
            } else {
                $this->info("{$name}: No old records found");
            }
        }

        // Find orphaned files
        $this->info("\nChecking for orphaned files...");
        $this->cleanupOrphanedFiles($dryRun);

        if (!$dryRun) {
            $this->info("\n✓ Cleanup completed! Total records deleted: {$totalDeleted}");
        } else {
            $this->info("\n✓ Dry run completed! {$totalDeleted} records would be deleted.");
        }

        return 0;
    }

    /**
     * Cleanup orphaned files
     */
    protected function cleanupOrphanedFiles($dryRun)
    {
        $folders = [
            'laporan_fundfactsheet',
            'laporan_mingguan',
            'laporan_bulanan',
            'laporan_tahunan',
        ];

        foreach ($folders as $folder) {
            $files = Storage::disk('public')->files($folder);

            foreach ($files as $file) {
                // Check if file exists in database
                $exists = FundFactSheet::where('file_path', $file)->exists() ||
                    LaporanMingguan::where('file_path', $file)->exists() ||
                    LaporanBulanan::where('file_path', $file)->exists() ||
                    LaporanTahunan::where('file_path', $file)->exists();

                if (!$exists) {
                    $this->warn("  Orphaned file: {$file}");

                    if (!$dryRun) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
        }
    }
}

// Register di app/Console/Kernel.php:
// protected $commands = [
//     \App\Console\Commands\CleanupOldFiles::class,
// ];

// Jalankan: php artisan asabri:cleanup-files --dry-run