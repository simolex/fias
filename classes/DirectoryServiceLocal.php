<?php
namespace Salxig\Fias\Classes;

use Salxig\Fias\Contracts\DirectoryService;
use Storage;
use File;
use DirectoryIterator;
use RarArchive;
use RarEntry;

use October\Rain\Exception\ApplicationException;

class DirectoryServiceLocal implements DirectoryService
{
	protected $pathSeparator;
	protected $workFolder;
	protected $listFilesStream;
	//protected $storageFias;

	public function __construct()
	{
		//$this->storageFias		= Storage::disk('local');
		$this->pathSeparator 	= '/';
		$this->workFolder 		= Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix('fias');
		$this->listFilesStream = [];

        /*if(File::isDirectoryEmpty($this->workFolder))
        {
            $this->storageFias->makeDirectory($this->makePath('full'));
            $this->storageFias->makeDirectory($this->makePath('delta'));
        }*/
	}

	public function isExists(string $type, int $version, string $format = 'xml'):bool
	{
		$pathToLocalFile = $this->makePath($type, $version, $format);
		return (File::exists($pathToLocalFile) && File::isFile($pathToLocalFile));


	}

	public function openStreamLocalFile(string $type, int $version, string $format = 'xml')
	{
		$keyStream = implode('_', compact('type', 'version', 'format'));

		$pathToLocalFile = $this->makePath('uploads'). $this->pathSeparator. $keyStream. '.~ar';

		$resMakeDirectory = $this->makeDirectory(File::dirname($pathToLocalFile));
		//if exeption

		$hLocal = fopen($pathToLocalFile, 'wb');

		if ($hLocal === false) {
            throw new ApplicationException(
                "Can't open local file for writing: {$pathToLocalFile}"
            );
        }


        $this->listFilesStream[$keyStream] = $hLocal;
        return $hLocal;
	}

	public function closeStreamLocalFile(string $type, int $version, string $format = 'xml')
	{
		$keyStream = implode('_', compact('type', 'version', 'format'));

		$pathToSourceFile = $this->makePath('uploads'). $this->pathSeparator. $keyStream. '.~ar';

		if(is_resource($this->listFilesStream[$keyStream]))
			fclose($this->listFilesStream[$keyStream]);

		$pathToDestFile = $this->makePath($type, $version, $format);
		$resMakeDirectory = $this->makeDirectory(File::dirname($pathToDestFile));

		return File::move($pathToSourceFile, $pathToDestFile, true);
	}

	public function getMaxFullVersion(string $format = 'xml')
	{
		if($versionAll = $this->getAllFullVersion($format)){
			array_multisort($versionAll,SORT_ASC, SORT_NUMERIC);

			return (int) array_pop($versionAll);
		}
		return false;

	}

	public function getMaxDeltaVersion(string $format = 'xml')
	{
		if($versionAll = $this->getAllDeltaVersion($format)){
			array_multisort($versionAll,SORT_ASC, SORT_NUMERIC);

			return (int) array_pop($versionAll);
		}
		return false;
	}

	public function getAllFullVersion(string $format = 'xml')
	{
		$pathFullStorage =  $this->makePath('full');

		if(File::isDirectory($pathFullStorage) && File::exists($pathFullStorage)){
			$dirs = new DirectoryIterator($pathFullStorage);
			$test = [];
	        foreach ($dirs as $node) {
	            if (substr($node->getFileName(), 0, 1) == '.') {
	                continue;
	            }

	            $pathVersion = $node->getPathname();

	            if (substr($pathVersion, 0, strlen($pathFullStorage)) == $pathFullStorage) {
		            $numVersion = ltrim(substr($pathVersion, strlen($pathFullStorage)), $this->pathSeparator);
		        }
	            $test[]= (int)$numVersion;
	        }
	        return $test;
	    }
	    return false;
	}

	public function getAllDeltaVersion(string $format = 'xml')
	{
		$pathFullStorage =  $this->makePath('delta');

		if(File::isDirectory($pathFullStorage) && File::exists($pathFullStorage)){
			$dirs = new DirectoryIterator($pathFullStorage);
			$test = [];
	        foreach ($dirs as $node) {
	            if (substr($node->getFileName(), 0, 1) == '.') {
	                continue;
	            }

	            $pathVersion = $node->getPathname();

	            if (substr($pathVersion, 0, strlen($pathFullStorage)) == $pathFullStorage) {
		            $numVersion = ltrim(substr($pathVersion, strlen($pathFullStorage)), $this->pathSeparator);
		        }
	            $test[]= (int)$numVersion;
	        }
	        return $test;
	    }
	    return false;
	}

	public function findFullPathByPattern(string $pattern, string $type, int $version, string $format = 'xml' ): array
	{
		$return = [];
		$pathPackageFias =  $this->makePath($type, $version, $format);
		$regexp = '/^' . implode('[^\/\.]+', array_map('preg_quote', explode('*', $pattern))) . '$/';

		$packageFias = RarArchive::open($pathPackageFias);
		//TODO: Exception
		$listFiasFiles = $packageFias->getEntries();
		//TODO: Exception
		foreach ($listFiasFiles as $fileFias) {
			if (!$fileFias->isDirectory() && preg_match($regexp, $fileFias->getName())) {
			$return[] = $pathPackageFias.'#'.$fileFias->getName();
		}

		return $return;
	}


	protected function makePath(string $type, int $version = 0, string $format = '')
	{
		switch ($type) {
			case 'full':
			case 'delta':
			case 'uploads':
				$localPath = $this->workFolder.	$this->pathSeparator. (string) $type;
				break;
			default:
				return '';
				break;
		}

		if($version > 0)
			$localPath .= $this->pathSeparator.	(string)$version;

		switch ($format) {
			case 'xml':
			case 'dbf':
				$localPath .= $this->pathSeparator.	$format. '.rar';
				break;
		}

		return $localPath;
	}

	private function makeDirectory(string $pathDirectory)
	{
		if((File::isDirectory($pathDirectory) && File::exists($pathDirectory))) return true;

		$parentDirectory = File::dirname($pathDirectory);
		if(!(File::isDirectory($parentDirectory) && File::exists($parentDirectory)))
			$this->makeDirectory($parentDirectory);

		return File::makeDirectory($pathDirectory);
	}

/*	private function checkParams(string $type, int $version = 0, string $format = '')
	{
		$resultCheck = true;

		switch ($type) {
			case 'full':
			case 'delta':
			case 'uploads':
				break;
			default:
				return false;
				break;
		}
		if($version < 0) return false;

		switch ($format) {
			case 'xml':
			case 'dbf':
			case '':
				break;
			default:
				return false;
				break;
		}
		return true;
	}*/
}