<?php

declare(strict_types=1);

namespace RodeoPHP\Console;

use Illuminate\Console\Command;

class UpgradeCommand extends Command
{
    protected $signature = 'rodeo:upgrade';

    protected $description = 'Republish the compiled panel assets';

    public function handle(): int
    {
        $this->call('vendor:publish', ['--tag' => 'rodeo-assets', '--force' => true]);

        $this->components->info('Panel assets refreshed.');

        return self::SUCCESS;
    }
}
