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
	public function getAllFullVersion();
	public function getAllDeltaVersion();
	public function getMaxFullVersion();
	public function getMaxDeltaVersion();
	public function openStreamLocalFile(string $type, int $version, string $format);
	public function closeStreamLocalFile(string $type, int $version, string $format);

}
