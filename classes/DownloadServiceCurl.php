<?php
namespace Salxig\Fias\Classes;

use Salxig\Fias\Contracts\DownloadService;
use Log;

class DownloadServiceCurl implements DownloadService
{
	protected $downloads;
	protected $curlHandler;

	protected $maxTriesReconnection;
	protected $countTries;
	protected $minReconnectonProgress;
	protected $waitReconnection;


	public $sizeTotal;

	public function __construct($dl_thread = 1)
	{
    	$this->downloads = [];
    	$this->curlHandler = curl_init();
    	$this->sizeTotal = 0;

    	$this->maxTriesReconnection = 5;
    	$this->minProgressReconnecton = 512*1024;
    	$this->waitReconnection = 180;
	}

	protected function has($value):bool
	{
		return array_key_exists($value, $this->downloads);
	}

	private function initTries()
	{
		$this->countTries = 0;
	}

	protected function canNextReconnection(int $progressPrev):bool
	{
		if($progressPrev = 0){
			sleep($this->waitReconnection);
		} else sleep(5);


		if($progressPrev<$this->minProgressReconnecton)
			$this->countTries +=1;
		else
			$this->countTries = 0;

		return (($this->countTries <= $this->maxTriesReconnection)?true:false);

	}

	protected function curlGetSize($url):int
	{
		curl_reset($this->curlHandler);

		$requestOptions = [
            CURLOPT_URL 			=> $url,
            CURLOPT_HTTPHEADER		=> ['Connection: Keep-Alive', 'Keep-Alive: 300'],
            CURLOPT_FOLLOWLOCATION 	=> true,
            //CURLOPT_HTTPHEADER		=> true,
            CURLOPT_NOBODY			=> true
        ];

        curl_setopt_array($this->curlHandler, $requestOptions);
        curl_exec($this->curlHandler);

        $contentLength = curl_getinfo($this->curlHandler, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        $httpStatus = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);



        if( $httpStatus == 200 || ($httpStatus > 300 && $httpStatus <= 308) ) {
	      return $contentLength;
	    }


	    //TODO: Exception to Error Code
	    else return 0;
    }

    public static function callbackProgress($resource, $dl_fullSize, $dl_curSize, $ul_fullSize = 0, $ul_curSize = 0, $resource_id)
    {
    	if($dl_fullSize > 0)
         echo $dl_curSize / $dl_fullSize  * 100;
    }

    public function getTest($url)
    {
    	$url_hash = md5($url);
    	return $this->downloads[$url_hash];

    }

	/**
   * Add a new file to the download service
   *
   * @param string  $url
   * @param Stream $resource
   *
   * @return $this
   * @throws ServiceAlreadyExists
   */
	public function add($url, $resource)
	{
		$url_hash = md5($url);
		if ((!$this->has($url_hash)) && is_resource($resource)) {
		  	//$service = new Service();

		  	//$closure($service);
			$fileSize = $this->curlGetSize($url);
			$this->sizeTotal += (int)$fileSize;

			$this->downloads[$url_hash] = compact('url','resource','fileSize');

		  return $this;
		}

	//throw new ServiceAlreadyExists("Service '" . $name . "' already exists.");
	}

  /**
   * Add a new files to the download service by array
   *
   * @param array $downloads
   *
   * @return $this
   *
   * @throws ServiceAlreadyExists
   * @throws ServiceMethodNotExists
   */
  public function addByArray(array $downloads = [])
  {
	if (!empty($downloads)) {
      foreach ($downloads as $url_hash => $download) {
      	$url_hash = md5($download['url']);
        if ((!$this->has($url_hash)) && is_resource($download['resource']))
		{
          //$service = new Service();

	      /*foreach ($methods as $method => $value) {
	        if (method_exists($service, $method)) {
	          $service->{$method}($value);
	        } else {
	            throw new ServiceMethodNotExists(sprintf(
	              "Method '%s' does not exists on the %s service.",
	              $method,
	              $name
	            ));
	          }
	      }*/
	      $download['fileSize'] = $this->curlGetSize($url);
	      $this->sizeTotal += (int)$download['fileSize'];

          $this->downloads[$url_hash] = $download;

          continue;
        }

        /*throw new ServiceAlreadyExists(sprintf(
          "Service '%s' already exists.",
          $name
        ));*/
      }
    }

    return $this;
  }

  public function run($progress = null)
  {


    if($progress && is_callable($progress)){
        $progress(0, 0, $this->sizeTotal);
    }

    $sizePrevDownloads=0;

  	foreach($this->downloads as $key => $OneDownload)
  	{
  		$curDownloadedSize = 0;

  		$this->initTries();
  		curl_reset($this->curlHandler);

  		$requestOptions = [
            CURLOPT_URL 			=> $OneDownload['url'],
            CURLOPT_HTTPHEADER		=> ['Connection: Keep-Alive', 'Keep-Alive: 300'],
            CURLOPT_FILE 			=> $OneDownload['resource'],
            CURLOPT_BUFFERSIZE		=> (128*1024),
            CURLOPT_USERAGENT 		=> 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
            // CURLOPT_VERBOSE			=> true,
            // CURLOPT_STDERR			=> fopen('/home/vagrant/code/octobercms/public/curl.log','a'),
            CURLOPT_FOLLOWLOCATION 	=> true,
            CURLOPT_NOPROGRESS      => false,
            // CURLOPT_TIMEOUT => 10,
            CURLOPT_LOW_SPEED_LIMIT => 1000,
            CURLOPT_LOW_SPEED_TIME	=> 30,
            CURLOPT_PROGRESSFUNCTION=> function ($resource, $downloadSize, $downloaded, $uploadSize, $uploaded) use ($progress, $sizePrevDownloads, &$curDownloadedSize) {

    			if($progress && is_callable($progress)){
                    $progress($downloaded + $curDownloadedSize, $sizePrevDownloads);
                }
    		}
        ];

        curl_setopt_array($this->curlHandler, $requestOptions);

       /* $resultCurl = curl_exec($this->curlHandler);

        $curDownloadedSize += curl_getinfo($this->curlHandler, CURLINFO_SIZE_DOWNLOAD);
        $httpStatus = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);*/
        do {

        	if($curDownloadedSize > 0)
        		curl_setopt($this->curlHandler, CURLOPT_RESUME_FROM, (float) $curDownloadedSize);

        	$resultCurl = curl_exec($this->curlHandler);

        	$dlProgress = curl_getinfo($this->curlHandler, CURLINFO_SIZE_DOWNLOAD);
        	$curDownloadedSize += $dlProgress;

        	$httpStatus = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);

        	$iterationResult = [
        		'httpStatus' 	=> $httpStatus,
        		'httpErrorTxt'	=> curl_error($this->curlHandler),
        		'httpErrorNum'	=> curl_errno($this->curlHandler),
        		'httpProgress'	=> $dlProgress,
        	];
        	$this->downloads[$key]['Result'][] = $iterationResult;

        } while (in_array(curl_errno($this->curlHandler), [CURLE_OPERATION_TIMEDOUT, CURLE_COULDNT_CONNECT]) &&
        		in_array($httpStatus, [200,206])  &&
        		$curDownloadedSize < $OneDownload['fileSize'] &&
        		$this->canNextReconnection($dlProgress));




        /*if($OneDownload['resource'])
        	fclose($OneDownload['resource']);*/

        //$httpStatus = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);
        //$test  = curl_getinfo($this->curlHandler);

        if ($resultCurl && in_array($httpStatus, [200,206])) {

            $sizePrevDownloads += (int)$OneDownload['fileSize'];
            unset($this->downloads[$key]);
            //$this->downloads[$key]['Result'] = true;

        } else {
            unset($this->downloads[$key]['url']);
            unset($this->downloads[$key]['resource']);
            /*$this->downloads[$key]['httpStatus'] = $httpStatus;
            $this->downloads[$key]['httpError'] = curl_error($this->curlHandler);
            $this->downloads[$key]['httpErrorNum'] = curl_errno($this->curlHandler);*/
        }
  	}

  	$this->sizeTotal = 0;

  	return (count($this->downloads) == 0)? true: $this->downloads;
  }

  public function __destruct()
  {
  	curl_close($this->curlHandler);
  }

}
