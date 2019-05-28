<?php namespace Salxig\Fias\Console;
use App;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use File;

class FiasDatabaseInstall extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'fias:install';

    /**
     * @var string The console command description.
     */
    protected $description = 'Install FIAS database.';

    protected $updateService;
    protected $downloadService;
    protected $storageFias;

    public function __construct()
    {
        parent::__construct();

        $this->setUpdateService();
        $this->setDownloadService();
        $this->setFiasStorage();
    }

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {

        $region_nums = $this->option('region_nums');
        $version_num = $this->option('version_num');
        //if(array_key_exists('region_nums'))

        $this->info(var_dump($this->storageFias->getStreamLocalFile('full',531,'xml')));
        //$this->info(var_dump($region_nums));
        //$this->info(var_dump($this->updateService->getUrlForDeltaData(530)));

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
            ['version_num', 'vn', InputOption::VALUE_OPTIONAL, 'Version number DB FIAS.'],
            //['version_date', 'vd', InputOption::VALUE_OPTIONAL, 'Date of version DB FIAS.', 'last'],
            ['region_nums', 'rn',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,//InputOption::VALUE_REQUIRED,
                'Numbers of region DB FIAS.'],
        ];
    }

    protected function setUpdateService():bool
    {
        $this->updateService = App::make('UpdateService');

        return isset($this->updateService);
    }

    protected function setDownloadService():bool
    {
        $this->downloadService = App::make('DownloadService');

        return isset($this->downloadService);
    }

    protected function setFiasStorage():bool
    {
        $this->storageFias = App::make('DirectoryService');

        return isset($this->storageFias);
    }


}
