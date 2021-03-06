<?php

namespace marvin255\fias;

/**
 * Интерфейс для объекта, который позволяет передавать объекты сервисов
 * между задачами, например, объекты pdo для связи с базой данных.
 */
interface ServiceLocatorInterface
{
    /**
     * Резолвит объект сервиса по указанному в параметре классу или интерфейсу.
     *
     * @param string $service
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function resolve(string $service);
}
