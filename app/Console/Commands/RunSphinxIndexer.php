<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RunSphinxIndexer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sphinx:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the Sphinx indexer to rebuild the index.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Run the indexer command using Symfony Process component
        $process = new Process(['indexer', '--all', '--rotate']);
        $process->setTimeout(600); // Set timeout to 10 minutes

        try {
            $process->mustRun();
            $this->info('Sphinx indexer ran successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error('Sphinx indexer failed: ' . $exception->getMessage());
        }
    }
}
