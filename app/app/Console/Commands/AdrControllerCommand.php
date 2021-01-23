<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Input\InputOption;

class AdrControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:controller-adr {className} {--u|usecase}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new controller with adr pattern';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'adr-controller';

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $name = $this->argument('className');

        if ($this->option('usecase')) {
            $this->call('make:usecase', [
                'name' => $name
            ]);
        }

        $this->call("make:responder", [
            'name' => $name
        ]);
        $this->call("make:action", [
            'name' => $name,
            '-u' => $this->option('usecase')
        ]);

        return true;
    }
}
