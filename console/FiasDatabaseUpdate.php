<?php namespace Salxig\Fias\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FiasDatabaseUpdate extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'fias:update';

    /**
     * @var string The console command description.
     */
    protected $description = 'Update FIAS database.';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {

        $options = $this->option();
        $this->info(var_dump($options));

    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['version_num', 'vn', InputOption::VALUE_OPTIONAL, 'Version number DB FIAS.', 'last'],
            ['version_date', 'vd', InputOption::VALUE_OPTIONAL, 'Date of version DB FIAS.', 'last'],
            ['region_nums', 'rn',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Numbers of region DB FIAS.'],
        ];
    }
}
