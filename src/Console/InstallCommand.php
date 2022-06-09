<?php

namespace AlhajiAki\PhoneNumberVerification\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phone-number-verification:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Phone number verification controllers';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Controllers...
        (new Filesystem())->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/App/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        $this->info('Phone number verification scaffolding installed successfully.');
    }
}
