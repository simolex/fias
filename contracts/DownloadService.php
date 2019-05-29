<?php

namespace Salxig\Fias\Contracts;

use Closure;
/**
 * Интерфейс для объекта, который обращается к сервису обновления ФИАС.
 */
interface DownloadService
{
    /**
     * Подготовливает сервис для скачивания файла
     * по ссылке из первого параметра в целевой поток,
     * указанный во втором параметре.
     *
     * @param string $urlToDownload
     * @param $resource
     *
     * @throws \RuntimeException
     */
    public function add(string $urlToDownload,  $resource);

    /**
     * Добавить ссылки и целевой поток из массива
     *
     *
     * @param array $downloads = ['url', 'resource']
     *
     *
     * @throws \RuntimeException
     */
    public function addByArray(array $downloads);

    /**
     * Запустить загрузку файлов
     *
     */
    public function run();

    public function getTest($url);



}