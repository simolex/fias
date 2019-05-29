<?php namespace Salxig\Fias\Console;
use App;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
//use Symfony\Component\Console\Helper\ProgressBar;
//
//use Symfony\Component\Console\Style\SymfonyStyle;

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
    protected $globalProgress;

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
        //$section1 = $this->output->section('globalProgress');

        $region_nums = $this->option('region_nums');
        $version_num = $this->option('version_num');
        //if(array_key_exists('region_nums'))
        if(!($this->storageFias->getMaxDeltaVersion())){
            $this->globalProgress = $this->output->createProgressBar(5);

            $this->globalProgress->setMessage('Get list update files ...');
            $resultUpdate = $this->updateService->getUrlForDeltaData(543);
            //$resultUpdate = $this->updateService->getUrlForCompleteData();

            $this->globalProgress->advance();
            $this->globalProgress->setMessage('Get stream file ...');

            $resultUpdate = $resultUpdate[0];
            $handleFile = $this->storageFias->openStreamLocalFile('delta',$resultUpdate['version']);
            $this->globalProgress->advance();
            $dlService = $this->downloadService->add($resultUpdate['url'], $handleFile);
            $this->globalProgress->advance();
            $size = $dlService->getTest($resultUpdate['url']);
            $dlService->run(function ($progress_value, $prev_progress, $max_value = null)
                {
                    if($max_value !== null){
                        //$this->info('max_value:'.$max_value);
                        $this->globalProgress->start($max_value);
                        $this->globalProgress->setRedrawFrequency($max_value / 20);
                    }

                    $this->globalProgress->setProgress($prev_progress+$progress_value);


                });
            $this->globalProgress->advance();
            $this->storageFias->closeStreamLocalFile('delta',$resultUpdate['version']);

            $this->globalProgress->finish();
        }

        //$this->info(var_dump($this->storageFias->getMaxFullVersion()));
        //$this->info(var_dump($size));
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
