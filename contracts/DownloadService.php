<?php

namespace Salxig\Fias\Contracts;

use Closure;
/**
 * Интерфейс для объекта, который обращается к сервису обновления ФИАС.
 */
interface DownloadService
{
    /**
     * Скачивает файл по ссылке из первого параметра в локальный файл,
     * указанный во втором параметре.
     *
     * @param string $urlToDownload
     * @param string $pathToLocalFile
     *
     * @throws \RuntimeException
     */
    public function download(string $urlToDownload, string $pathToLocalFile);

    /**
     * Скачивает файл по ссылке из первого параметра в локальный файл,
     * указанный во втором параметре.
     *
     * @param string $urlToDownload
     * @param string $pathToLocalFile
     *
     * @throws \RuntimeException
     */
    public function downloadTo(string $urlToDownload, Closure $FileStream);

}