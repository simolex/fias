<?php
namespace Salxig\Fias\Contracts;

interface DirectoryService
{
	/**
     * Создает папку и все родительские.
     *
     * @return bool
     */
	//public function create(): bool;
	public function isExists(string $type, int $version, string $format):bool;
	public function getMaxFullVersion(string $format);
	public function getMaxDeltaVersion(string $format);
	public function getStreamLocalFile(string $type, int $version, string $format);

}
