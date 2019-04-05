<?php namespace Salxig\Fias\Components;

use Cms\Classes\ComponentBase;
use Artisaninweb\SoapWrapper\SoapWrapper;
//use SoapClient;;
use Request;


class Weather extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'weather Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'country' => [
                'title'             => 'Country',
                'type'              => 'dropdown',
                'default'           => 'us'
            ],
            'state' => [
                'title'             => 'State',
                'type'              => 'dropdown',
                'default'           => 'dc',
                'depends'           => ['country'],
                'placeholder'       => 'Select a state'
            ],
            'city' => [
                'title'             => 'City',
                'type'              => 'string',
                'default'           => 'Washington',
                'placeholder'       => 'Enter the city name',
                'validationPattern' => '^[0-9a-zA-Z\s]+$',
                'validationMessage' => 'The City field is required.'
            ],
            'units' => [
                'title'             => 'Units',
                'description'       => 'Units for the temperature and wind speed',
                'type'              => 'dropdown',
                'default'           => 'imperial',
                'placeholder'       => 'Select units',
                'options'           => ['metric'=>'Metric', 'imperial'=>'Imperial']
            ]
        ];
    }

    protected function loadCountryData()
    {
        return json_decode(file_get_contents(__DIR__.'/../data/countries-and-states.json'), true);
    }

    public function getCountryOptions()
    {
        $countries = $this->loadCountryData();
        $result = [];

        foreach ($countries as $code=>$data)
            $result[$code] = $data['n'];

        return $result;
    }

    public function getStateOptions()
    {
        $countries = $this->loadCountryData();
        $countryCode = Request::input('country');
        return isset($countries[$countryCode]) ? $countries[$countryCode]['s'] : [];
    }

    public function info()
    {

        $urlsoap = "https://fias.nalog.ru/WebServices/Public/DownloadService.asmx?WSDL";
        $soapWrapper = new SoapWrapper();
        $soapWrapper->add('Fias', function ($service) use ($urlsoap) {
              $service->wsdl($urlsoap)
                    ->trace(true);
        });      
        /*$client = new SoapClient($urlsoap, [
                'exceptions' => true,
                'soap_version'   => SOAP_1_2,
            ]);
        $res = $client->GetLastDownloadFileInfo();*/

        $res = $soapWrapper->call('Fias.GetLastDownloadFileInfo',[]);

        /*$json = file_get_contents(sprintf(
            "http://api.openweathermap.org/data/2.5/weather?q=%s,%s,%s&units=%s&id=524901&appid=b1b15e88fa797225412429c1c50c122a1", 
            $this->property('city'),
            $this->property('state'),
            $this->property('country'),
            $this->property('units')
        ));
*/
        return $res->GetLastDownloadFileInfoResult->VersionId;//json_decode($json);
    }

    public function onRun()
    {
        $this->addCss('/plugins/rainlab/weather/assets/css/weather.css');
        $this->page['weatherInfo'] = $this->info();
    }
}
