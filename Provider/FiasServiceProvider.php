<?php

namespace Salxig\Fias\Provider;

use October\Rain\Support\ServiceProvider;
//use Salxig\Fias\Contracts\UpdateService;
use Salxig\Fias\Classes\UpdateServiceSoap;


class FiasServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('UpdateService', UpdateServiceSoap::class);
    }

}