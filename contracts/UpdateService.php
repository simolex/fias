<?php 

namespace Salxig\Fias\Contracts;

/**
 * Интерфейс для объекта, который обращается к сервису обновления ФИАС.
 */
interface UpdateService
{
    /**
     * Возвращает ссылку на файл с полной выгрузкой и его версию.
     *
     * @return array
     */
    public function getUrlForCompleteData(): array;

    /**
     * Возвращает ссылку на файл обновлений для указанной версии.
     *
     * @param string $fiasVersion
     *
     * @return array
     */
    public function getUrlForDeltaData(int $fiasVersion): array;
}