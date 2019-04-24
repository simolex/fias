<?php
namespace Salxig\Fias\Contracts;

interface DirectoryService
{
	/**
     * Создает папку и все родительские.
     *
     * @return bool
     */
	public function create(): bool;
}
