<?php return [

    
    'updateservice_wsdl'   => 'https://fias.nalog.ru/WebServices/Public/DownloadService.asmx?WSDL',
    'updateservice_format'        => 'xml',

    

    // This contains the Laravel Packages that you want this plugin to utilize listed under their package identifiers
    'packages' => [
        'artisaninweb/laravel-soap' => [
            // Service providers to be registered by your plugin
            'providers' => [
                '\Artisaninweb\SoapWrapper\ServiceProvider',
            ],

            // Aliases to be registered by your plugin in the form of $alias => $pathToFacade
            'aliases' => [
                'SoapWrapper' => '\Artisaninweb\SoapWrapper\Facade',
            ],
        ],
    ],
];