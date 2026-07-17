<?php

namespace MTechOrg\Gitstamp\Commands;

use Illuminate\Console\Command;
use MTechOrg\Gitstamp\Gitstamp;

class GenerateGitstampCommand extends Command
{
    protected $signature = 'gitstamp:generate';

    protected $description = 'Generate the deploy-time version stamp (date + short git SHA)';

    public function handle(Gitstamp $gitstamp): int
    {
        $value = $gitstamp->generate();

        $this->info("Gitstamp generated: {$value} -> ".config('gitstamp.path'));

        return self::SUCCESS;
    }
}
