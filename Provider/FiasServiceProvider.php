<?php

namespace Salxig\Fias\Provider;

use October\Rain\Support\ServiceProvider;
use Salxig\Fias\Contracts\UpdateService;
use Salxig\Fias\Classes\UpdateServiceSoap;
use Salxig\Fias\Contracts\DownloadService;
use Salxig\Fias\Classes\DownloadServiceCurl;



class FiasServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton('UpdateService', UpdateServiceSoap::class);
        $this->app->singleton('DownloadService', DownloadServiceCurl::class);

    }

}