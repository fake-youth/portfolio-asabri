$disk = \Illuminate\Support\Facades\Storage::disk('public');
$path = 'test_preview_file.pdf';
$disk->put($path, 'Dummy PDF Content');
$fullPath = $disk->path($path);
echo "Full Path: " . $fullPath . PHP_EOL;
echo "File Exists (PHP): " . (file_exists($fullPath) ? 'YES' : 'NO') . PHP_EOL;

try {
$response = response()->file($fullPath);
echo "Response created: " . get_class($response) . PHP_EOL;
} catch (\Throwable $e) {
echo "Error: " . $e->getMessage() . PHP_EOL;
}

$disk->delete($path);