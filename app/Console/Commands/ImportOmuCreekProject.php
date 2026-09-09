<?php

namespace App\Console\Commands;

use App\Actions\Projects\ImportOmuCreekProject as ImportOmuCreekProjectAction;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('selotemna:import-omu-creek {--force : Overwrite an existing Omu Creek record with verified configuration content}')]
#[Description('Import the verified Omu Creek project into the project content database')]
class ImportOmuCreekProject extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ImportOmuCreekProjectAction $importer): int
    {
        $result = $importer->handle((bool) $this->option('force'));

        if (! $result->imported) {
            $this->components->warn('Omu Creek already exists. No content was changed. Use --force only to restore the verified configuration source.');

            return self::SUCCESS;
        }

        $this->components->info('Omu Creek was imported as a published, featured Upcoming Project.');

        return self::SUCCESS;
    }
}
