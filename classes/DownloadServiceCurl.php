<?php
namespace Salxig\Fias\Classes;

use Salxig\Fias\Contracts\DownloadService;

class DownloadServiceCurl implements DownloadService
{
	protected $downloads;
	protected $curlHandler;
	protected $sizeTotal;

	public function __construct($dl_thread = 1)
	{
    	$this->downloads = [];
    	$this->curlHandler = curl_init();
    	$this->sizeTotal = 0;
	}

	protected function has($value):bool
	{
		return array_key_exists($value, $this->downloads);
	}

	protected function curlGetSize($url):int
	{
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

        curl_reset($this->curlHandler);

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
  	if(is_callable($progress) && $progress!=null)

  	foreach($this->downloads as $key => $OneDownload)
  	{


  		$requestOptions = [
            CURLOPT_URL 			=> $OneDownload['url'],
            CURLOPT_HTTPHEADER		=> ['Connection: Keep-Alive', 'Keep-Alive: 300'],
            CURLOPT_FILE 			=> $OneDownload['resource'],
            CURLOPT_FOLLOWLOCATION 	=> true,
            CURLOPT_PROGRESSFUNCTION=> function ($resource, $downloadSize, $downloaded, $uploadSize, $uploaded) use ($key) {
    			$this->callbackProgress($resource, $downloadSize, $downloaded, $uploadSize, $uploaded, $key);
    		}
        ];

        curl_setopt_array($this->curlHandler, $requestOptions);
        $resultCurl = curl_exec($this->curlHandler);

        /*if($OneDownload['resource'])
        	fclose($OneDownload['resource']);*/

        $httpStatus = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);

        if ($resultCurl === false || $httpStatus !== 200) {
            unset($this->downloads[$key]);
        } else {
            unset($this->downloads[$key]['url']);
            unset($this->downloads[$key]['resource']);
            $this->downloads[$key]['httpStatus'] = $httpStatus;
            $this->downloads[$key]['httpError']= curl_error($this->curlHandler);
        }

        curl_reset($this->curlHandler);
  	}
  	$this->sizeTotal = 0;
  }

  public function __destruct()
  {
  	curl_close($this->curlHandler);
  }

}
