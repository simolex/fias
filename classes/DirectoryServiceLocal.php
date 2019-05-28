<?php
namespace Salxig\Fias\Classes;

use Salxig\Fias\Contracts\DirectoryService;
use Storage;
use File;

use October\Rain\Exception\ApplicationException;

class DirectoryServiceLocal implements DirectoryService
{
	protected $pathSeparator;
	protected $workFolder;
	//protected $storageFias;

	public function __construct()
	{
		//$this->storageFias		= Storage::disk('local');
		$this->pathSeparator 	= '/';
		$this->workFolder 		= Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix('fias');

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

	public function getStreamLocalFile(string $type, int $version, string $format = 'xml')
	{
		$pathToLocalFile = $this->makePath($type, $version, $format);

		$resMakeDirectory = $this->makeDirectory(File::dirname($pathToLocalFile));
		//if exeption

		$hLocal = fopen($pathToLocalFile, 'wb');

		if ($hLocal === false) {
            throw new ApplicationException(
                "Can't open local file for writing: {$pathToLocalFile}"
            );
        }
        return $hLocal;
	}

	public function getMaxFullVersion($format = 'xml')
	{
		return;
	}

	public function getMaxDeltaVersion($format = 'xml')
	{
		return;
	}


	protected function makePath(string $type, int $version = 0, string $format = '')
	{
		switch ($type) {
			case 'full':
			case 'delta':
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
}