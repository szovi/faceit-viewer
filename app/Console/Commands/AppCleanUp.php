<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AppCleanUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting app cleanup...');

        Artisan::call('cache:clear');
        $this->info('✅ Cache cleared');

        Artisan::call('route:clear');
        $this->info('✅ Route cache cleared');

        Artisan::call('config:clear');
        $this->info('✅ Config cache cleared');

        Artisan::call('view:clear');
        $this->info('✅ Compiled views cleared');

        Artisan::call('clear-compiled');
        $this->info('✅ Compiled files cleared');

        if (File::exists(storage_path('framework/sessions'))) {
            File::cleanDirectory(storage_path('framework/sessions'));
            $this->info('✅ Sessions cleared');
        }

        if (File::exists(storage_path('logs'))) {
            File::cleanDirectory(storage_path('logs'));
            $this->info('✅ Logs cleared');
        }

        $this->info('App cleanup finished successfully!');
        return CommandAlias::SUCCESS;
    }
}
