<?php namespace Salxig\Fias\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Fileinfo Back-end Controller
 */
class Fileinfo extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    private $soap_wsdl = "https://fias.nalog.ru/WebServices/Public/DownloadService.asmx?WSDL";

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Salxig.Fias', 'fias', 'fileinfo');
    }

    public static function UpdateList()
    {

    }
}
