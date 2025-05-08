<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UploadLogoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:upload-logo {logo_path : The path to the logo file on your local system}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload company logo and set it in the settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $localLogoPath = $this->argument('logo_path');
        
        // Check if file exists
        if (!file_exists($localLogoPath)) {
            $this->error("File does not exist: {$localLogoPath}");
            return 1;
        }
        
        $this->info("Found logo file: {$localLogoPath}");
        
        // Get file extension
        $extension = pathinfo($localLogoPath, PATHINFO_EXTENSION);
        $filename = 'company_logo.' . $extension;
        
        // Store in public storage
        $storagePath = 'logos/' . $filename;
        $fileContent = file_get_contents($localLogoPath);
        
        Storage::disk('public')->put($storagePath, $fileContent);
        $this->info("Logo uploaded to: {$storagePath}");
        
        // Update or create the setting
        Setting::updateOrCreate(
            ['key_name' => 'logo_path'],
            ['value' => $storagePath]
        );
        
        $this->info("Logo path saved in settings as: {$storagePath}");
        $this->info("You can view your logo at: " . asset('storage/' . $storagePath));
        
        return 0;
    }
}
