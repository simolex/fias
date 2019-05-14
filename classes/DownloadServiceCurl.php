<?php
namespace Salxig\Fias\Classes;

use Salxig\Fias\Contracts\DownloadService;

class DownloadServiceCurl implements DownloadService
{
	protected $dowloades;
	protected $curlHandler;

	public function __construct($dl_thread = 1)
	{
    	$this->downloads = [];
    	$this->curlHandler = curl_init();
	}

	protected function curlGetSize($url):int
	{
		$requestOptions = [
            CURLOPT_URL 			=> $url,
            CURLOPT_HTTPHEADER		=> ['Connection: Keep-Alive', 'Keep-Alive: 300'],
            CURLOPT_FOLLOWLOCATION 	=> true,
            CURLOPT_HTTPHEADER		=> true,
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
    if (!$this->has($url_hash)) {
      //$service = new Service();

      //$closure($service);
      $fileSize = $this->curlGetSize($url);

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
        if (!$this->has($url_hash)) {
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
	      $downloads['fileSize'] = $this->curlGetSize($url);


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

  public function __destruct()
  {
  	curl_close($this->curlHandler);
  }

}

