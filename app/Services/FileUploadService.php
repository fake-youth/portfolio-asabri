<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload file PDF ke storage
     */
    public function uploadPDF(UploadedFile $file, string $folder): string
    {
        $filename = $this->generateFileName($file);
        $path = $file->storeAs($folder, $filename, 'public');

        return $path;
    }

    /**
     * Delete file dari storage
     */
    public function deleteFile(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Update file (delete old, upload new)
     */
    public function updateFile(string $oldPath, UploadedFile $newFile, string $folder): string
    {
        // Delete old file
        $this->deleteFile($oldPath);

        // Upload new file
        return $this->uploadPDF($newFile, $folder);
    }

    /**
     * Generate unique filename
     */
    private function generateFileName(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitized = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
        $extension = $file->getClientOriginalExtension();

        return time() . '_' . $sanitized . '.' . $extension;
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSize(string $path): string
    {
        if (!Storage::disk('public')->exists($path)) {
            return '0 KB';
        }

        $bytes = Storage::disk('public')->size($path);

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Check if file exists
     */
    public function fileExists(string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }

    /**
     * Get file URL
     */
    public function getFileUrl(string $path): string
    {
        return asset('storage/' . $path);
    }
}