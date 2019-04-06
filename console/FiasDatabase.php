<?php namespace Salxig\Fias\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FiasDatabase extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'fias:database';

    /**
     * @var string The console command description.
     */
    protected $description = 'Install/Update FIAS database.';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->output->writeln('Hello world!');
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['action', InputArgument::OPTIONAL, '"install" or "update" database.','update'],
        ];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['version_num', 'vn', InputOption::VALUE_OPTIONAL, 'Version number DB FIAS.', 'max'],
            ['version_date', 'vd', InputOption::VALUE_OPTIONAL, 'Date of version DB FIAS.', 'max'],
            ['region_nums', 'rn',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Numbers of region DB FIAS.'],
        ];
    }
}
