<?php namespace Salxig\Fias\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FiasDatabaseDelete extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'fias:delete';

    /**
     * @var string The console command description.
     */
    protected $description = 'Delete FIAS database.';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {

        $region_nums = $this->option('region_nums');
        //if(array_key_exists('region_nums'))
        $this->info(var_dump($region_nums));

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
            ['region_nums', 'rn',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Numbers of region DB FIAS.'],
        ];
    }
}
