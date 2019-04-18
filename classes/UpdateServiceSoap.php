<?php

namespace Salxig\Fias\Classes;
use Config;
use SoapClient;
use Salxig\Fias\Contracts\UpdateService;
/**
 * Объекта, который обращается к сервису обновления ФИАС.
 */
class UpdateServiceSoap implements UpdateService
{
    /**
     * @const
     */
    
    //const DEFAULT_FIAS_WSDL = 'http://fias.nalog.ru/WebServices/Public/DownloadService.asmx?WSDL';
    protected $fias_wsdl;

    /**
     * @var \SoapClient
     */
    protected $soapClient;

    /**
     * Задает SoapClient, если объет не задан, то создает самостоятельно.
     *
     * @param \SoapClient $soapClient
     */
    public function __construct(SoapClient $soapClient = null)
    {
        $fias_wsdl = Config::get('salxig.fias::updateservice_wsdl');

        if ($soapClient === null) {
            $soapClient = new SoapClient($fias_wsdl, [
                'exceptions' => true,
            ]);
        }

        $this->soapClient = $soapClient;
    }

    /**
     * @inheritdoc
     */
    public function getUrlForCompleteData(): array
    {
        $res = $this->soapClient->GetLastDownloadFileInfo();

        return [
            'url' => $res->GetLastDownloadFileInfoResult->FiasCompleteXmlUrl,
            'version' => $res->GetLastDownloadFileInfoResult->VersionId,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getUrlForDeltaData(int $fiasVersion): array
    {
        $return = [];

        $res = $this->soapClient->GetAllDownloadFileInfo();
        $versions = [];
        $versionsSort = [];
        foreach ($res->GetAllDownloadFileInfoResult->DownloadFileInfo as $key => $version) {
            $versions[$key] = (array) $version;
            $versionsSort[$key] = (int) $version->VersionId;
        }
        array_multisort($versionsSort, SORT_ASC, $versions);

        foreach ($versions as $version) {
            if ($version['VersionId'] <= $fiasVersion) {
                continue;
            }
            $return[] = [
                'url' => $version['FiasDeltaXmlUrl'],
                'version' => $version['VersionId'],
            ];
            //break;
        }

        return $return;
    }
}
