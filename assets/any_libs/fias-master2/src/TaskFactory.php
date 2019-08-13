<?php

namespace marvin255\fias;

use marvin255\fias\task\InsertData;
use marvin255\fias\task\UpdateData;
use marvin255\fias\task\DeleteData;
use InvalidArgumentException;

/**
 * Фабрика, которая собирает объекты для сущностей фиас.
 *
 * Все сущности описны в массиве сущностей, на основании массива и типа работы
 * будет собран объект задачи с соответствующими настройками.
 * Для настройки достаточно указать название таблицы в бд и, если
 * требуется, соответствия между полями в файле и в таблице бд.
 */
class TaskFactory
{
    /**
     * Массив с описаниями сущностей фиас.
     *
     * @var array
     */
    protected $entitiesDescription = [
        'Object' => [
            'xmlPathToNode' => '/AddressObjects/Object',
            'primary' => 'AOID',
            'xmlSelect' => [
                'AOGUID' => '@AOGUID',
                'FORMALNAME' => '@FORMALNAME',
                'REGIONCODE' => '@REGIONCODE',
                'AUTOCODE' => '@AUTOCODE',
                'AREACODE' => '@AREACODE',
                'CITYCODE' => '@CITYCODE',
                'CTARCODE' => '@CTARCODE',
                'PLACECODE' => '@PLACECODE',
                'PLANCODE' => '@PLANCODE',
                'STREETCODE' => '@STREETCODE',
                'EXTRCODE' => '@EXTRCODE',
                'SEXTCODE' => '@SEXTCODE',
                'OFFNAME' => '@OFFNAME',
                'POSTALCODE' => '@POSTALCODE',
                'IFNSFL' => '@IFNSFL',
                'TERRIFNSFL' => '@TERRIFNSFL',
                'IFNSUL' => '@IFNSUL',
                'TERRIFNSUL' => '@TERRIFNSUL',
                'OKATO' => '@OKATO',
                'OKTMO' => '@OKTMO',
                'UPDATEDATE' => '@UPDATEDATE',
                'SHORTNAME' => '@SHORTNAME',
                'AOLEVEL' => '@AOLEVEL',
                'PARENTGUID' => '@PARENTGUID',
                'AOID' => '@AOID',
                'PREVID' => '@PREVID',
                'NEXTID' => '@NEXTID',
                'CODE' => '@CODE',
                'PLAINCODE' => '@PLAINCODE',
                'ACTSTATUS' => '@ACTSTATUS',
                'CENTSTATUS' => '@CENTSTATUS',
                'OPERSTATUS' => '@OPERSTATUS',
                'CURRSTATUS' => '@CURRSTATUS',
                'STARTDATE' => '@STARTDATE',
                'ENDDATE' => '@ENDDATE',
                'NORMDOC' => '@NORMDOC',
                'LIVESTATUS' => '@LIVESTATUS',
                //'CADNUM' => '@CADNUM',
                'DIVTYPE' => '@DIVTYPE',
            ],
            'insertFilePattern' => 'AS_ADDROBJ_*.XML',
            'deleteFilePattern' => 'AS_DEL_ADDROBJ_*.XML',
        ],
        'House' => [
            'xmlPathToNode' => '/Houses/House',
            'primary' => 'HOUSEID',
            'xmlSelect' => [
                'HOUSEID' => '@HOUSEID',
                'POSTALCODE' => '@POSTALCODE',
                'IFNSFL' => '@IFNSFL',
                'TERRIFNSFL' => '@TERRIFNSFL',
                'IFNSUL' => '@IFNSUL',
                'TERRIFNSUL' => '@TERRIFNSUL',
                'OKATO' => '@OKATO',
                'OKTMO' => '@OKTMO',
                'UPDATEDATE' => '@UPDATEDATE',
                'HOUSENUM' => '@HOUSENUM',
                'ESTSTATUS' => '@ESTSTATUS',
                'BUILDNUM' => '@BUILDNUM',
                'STRUCNUM' => '@STRUCNUM',
                'STRSTATUS' => '@STRSTATUS',
                'HOUSEGUID' => '@HOUSEGUID',
                'AOGUID' => '@AOGUID',
                'STARTDATE' => '@STARTDATE',
                'ENDDATE' => '@ENDDATE',
                'STATSTATUS' => '@STATSTATUS',
                'NORMDOC' => '@NORMDOC',
                'COUNTER' => '@COUNTER',
                'CADNUM' => '@CADNUM',
                'DIVTYPE' => '@DIVTYPE',
            ],
            'insertFilePattern' => 'AS_HOUSE_*.XML',
            'deleteFilePattern' => 'AS_DEL_HOUSE_*.XML',
            'bulkSize' => 100,
        ],
        'Stead' => [
            'xmlPathToNode' => '/Steads/Stead',
            'primary' => 'STEADID',
            'xmlSelect' => [
                'STEADID' => '@STEADID',
                'NUMBER' => '@NUMBER',
                'POSTALCODE' => '@POSTALCODE',
                'REGIONCODE' => '@REGIONCODE',
                'IFNSFL' => '@IFNSFL',
                'TERRIFNSFL' => '@TERRIFNSFL',
                'IFNSUL' => '@IFNSUL',
                'TERRIFNSUL' => '@TERRIFNSUL',
                'OKATO' => '@OKATO',
                'OKTMO' => '@OKTMO',
                'UPDATEDATE' => '@UPDATEDATE',
                'STEADGUID' => '@STEADGUID',
                'PARENTGUID' => '@PARENTGUID',
                'STARTDATE' => '@STARTDATE',
                'ENDDATE' => '@ENDDATE',
                'PREVID' => '@PREVID',
                'NEXTID' => '@NEXTID',
                'OPERSTATUS' => '@OPERSTATUS',
                'LIVESTATUS' => '@LIVESTATUS',
                'NORMDOC' => '@NORMDOC',
                'CADNUM' => '@CADNUM',
                'DIVTYPE' => '@DIVTYPE',
            ],
            'insertFilePattern' => 'AS_STEAD_*.XML',
            'deleteFilePattern' => 'AS_DEL_STEAD_*.XML',
        ],
        'Room' => [
            'xmlPathToNode' => '/Rooms/Room',
            'primary' => 'ROOMID',
            'xmlSelect' => [
                'ROOMID' => '@ROOMID',
                'POSTALCODE' => '@POSTALCODE',
                'REGIONCODE' => '@REGIONCODE',
                'FLATNUMBER' => '@FLATNUMBER',
                'FLATTYPE' => '@FLATTYPE',
                'ROOMNUMBER' => '@ROOMNUMBER',
                'ROOMTYPE' => '@ROOMTYPE',
                'UPDATEDATE' => '@UPDATEDATE',
                'ROOMGUID' => '@ROOMGUID',
                'HOUSEGUID' => '@HOUSEGUID',
                'STARTDATE' => '@STARTDATE',
                'ENDDATE' => '@ENDDATE',
                'PREVID' => '@PREVID',
                'NEXTID' => '@NEXTID',
                'LIVESTATUS' => '@LIVESTATUS',
                'NORMDOC' => '@NORMDOC',
                'CADNUM' => '@CADNUM',
                'ROOMCADNUM' => '@ROOMCADNUM'
                'OPERSTATUS' => '@OPERSTATUS',
            ],
            'insertFilePattern' => 'AS_ROOM_*.XML',
            'deleteFilePattern' => 'AS_DEL_ROOM_*.XML',
            'bulkSize' => 100,
        ],
        'NormativeDocument' => [
            'xmlPathToNode' => '/NormativeDocumentes/NormativeDocument',
            'primary' => 'NORMDOCID',
            'xmlSelect' => [
                'NORMDOCID' => '@NORMDOCID',
                'DOCNAME' => '@DOCNAME',
                'DOCDATE' => '@DOCDATE',
                'DOCNUM' => '@DOCNUM',
                'DOCTYPE' => '@DOCTYPE',
                'DOCIMGID' => '@DOCIMGID',
            ],
            'insertFilePattern' => 'AS_NORMDOC_*.XML',
            'deleteFilePattern' => 'AS_DEL_NORMDOC_*.XML',
        ],
        'AddressObjectType' => [
            'xmlPathToNode' => '/AddressObjectTypes/AddressObjectType',
            'primary' => 'KOD_T_ST',
            'xmlSelect' => [
                'KOD_T_ST' => '@KOD_T_ST',
                'LEVEL' => '@LEVEL',
                'SCNAME' => '@SCNAME',
                'SOCRNAME' => '@SOCRNAME',
            ],
            'insertFilePattern' => 'AS_SOCRBASE_*.XML',
            'deleteFilePattern' => 'AS_DEL_SOCRBASE_*.XML',
        ],
        'CurrentStatus' => [
            'xmlPathToNode' => '/CurrentStatuses/CurrentStatus',
            'primary' => 'CURENTSTID',
            'xmlSelect' => [
                'CURENTSTID' => '@CURENTSTID',
                'NAME' => '@NAME',
            ],
            'insertFilePattern' => 'AS_CURENTST_*.XML',
            'deleteFilePattern' => 'AS_DEL_CURENTST_*.XML',
        ],
        'ActualStatus' => [
            'xmlPathToNode' => '/ActualStatuses/ActualStatus',
            'primary' => 'ACTSTATID',
            'xmlSelect' => [
                'ACTSTATID' => '@ACTSTATID',
                'NAME' => '@NAME',
            ],
            'insertFilePattern' => 'AS_ACTSTAT_*.XML',
            'deleteFilePattern' => 'AS_DEL_ACTSTAT_*.XML',
        ],
        'OperationStatus' => [
            'xmlPathToNode' => '/OperationStatuses/OperationStatus',
            'primary' => 'OPERSTATID',
            'xmlSelect' => [
                'OPERSTATID' => '@OPERSTATID',
                'NAME' => '@NAME',
            ],
            'insertFilePattern' => 'AS_OPERSTAT_*.XML',
            'deleteFilePattern' => 'AS_DEL_OPERSTAT_*.XML',
        ],
        'CenterStatus' => [
            'xmlPathToNode' => '/CenterStatuses/CenterStatus',
            'primary' => 'CENTERSTID',
            'xmlSelect' => [
                'CENTERSTID' => '@CENTERSTID',
                'NAME' => '@NAME',
            ],
            'insertFilePattern' => 'AS_CENTERST_*.XML',
            'deleteFilePattern' => 'AS_DEL_CENTERST_*.XML',
        ],
        'IntervalStatus' => [
            'xmlPathToNode' => '/IntervalStatuses/IntervalStatus',
            'primary' => 'INTVSTATID',
            'xmlSelect' => [
                'INTVSTATID' => '@INTVSTATID',
                'NAME' => '@NAME',
            ],
            'insertFilePattern' => 'AS_INTVSTAT_*.XML',
            'deleteFilePattern' => 'AS_DEL_INTVSTAT_*.XML',
        ],
        'HouseStateStatus' => [
            'xmlPathToNode' => '/HouseStateStatuses/HouseStateStatus',
            'primary' => 'HOUSESTID',
            'xmlSelect' => [
                'HOUSESTID' => '@HOUSESTID',
                'NAME' => '@NAME',
            ],
            'insertFilePattern' => 'AS_HSTSTAT_*.XML',
            'deleteFilePattern' => 'AS_DEL_HSTSTAT_*.XML',
        ],
        'EstateStatus' => [
            'xmlPathToNode' => '/EstateStatuses/EstateStatus',
            'primary' => 'ESTSTATID',
            'xmlSelect' => [
                'ESTSTATID' => '@ESTSTATID',
                'NAME' => '@NAME',
                'SHORTNAME' => '@SHORTNAME',
            ],
            'insertFilePattern' => 'AS_ESTSTAT_*.XML',
            'deleteFilePattern' => 'AS_DEL_ESTSTAT_*.XML',
        ],
        'StructureStatus' => [
            'xmlPathToNode' => '/StructureStatuses/StructureStatus',
            'primary' => 'STRSTATID',
            'xmlSelect' => [
                'STRSTATID' => '@STRSTATID',
                'NAME' => '@NAME',
                'SHORTNAME' => '@SHORTNAME',
            ],
            'insertFilePattern' => 'AS_STRSTAT_*.XML',
            'deleteFilePattern' => 'AS_DEL_STRSTAT_*.XML',
        ],
        'FlatType' => [
            'xmlPathToNode' => '/FlatTypes/FlatType',
            'primary' => 'FLTYPEID',
            'xmlSelect' => [
                'FLTYPEID' => '@FLTYPEID',
                'NAME' => '@NAME',
                'SHORTNAME' => '@SHORTNAME',
            ],
            'insertFilePattern' => 'AS_FLATTYPE_*.XML',
            'deleteFilePattern' => 'AS_DEL_FLATTYPE_*.XML',
        ],
        'NormativeDocumentType' => [
            'xmlPathToNode' => '/NormativeDocumentTypes/NormativeDocumentType',
            'primary' => 'NDTYPEID',
            'xmlSelect' => [
                'NDTYPEID' => '@NDTYPEID',
                'NAME' => '@NAME',
            ],
            'insertFilePattern' => 'AS_NDOCTYPE_*.XML',
            'deleteFilePattern' => 'AS_DEL_NDOCTYPE_*.XML',
        ],
        'RoomType' => [
            'xmlPathToNode' => '/RoomTypes/RoomType',
            'primary' => 'RMTYPEID',
            'xmlSelect' => [
                'RMTYPEID' => '@RMTYPEID',
                'NAME' => '@NAME',
                'SHORTNAME' => '@SHORTNAME',
            ],
            'insertFilePattern' => 'AS_ROOMTYPE_*.XML',
            'deleteFilePattern' => 'AS_DEL_ROOMTYPE_*.XML',
        ],


    ];

    /**
     * Создает объект для чтения даных из файла и создания новых записей.
     *
     * @param string $entity    Название сущности фиас, длякоторой создается объект
     * @param string $tableName Название таблицы, в которую будет произведена запись
     * @param array  $fields    Массив с соответствием полей, вида "поле в таблице => поле в файле"
     *
     * @return \marvin255\fias\TaskInterface
     */
    public function inserter(string $entity, string $tableName, array $fields = null): TaskInterface
    {
        $entityDescription = $this->getEntityDescription($entity);

        $filePattern = $entityDescription['insertFilePattern'];
        $pathToNode = $entityDescription['xmlPathToNode'];
        $select = $fields ?: $entityDescription['xmlSelect'];
        $bulk = !empty($entityDescription['bulkSize']) ? $entityDescription['bulkSize'] : 200;

        return new InsertData($tableName, $filePattern, $pathToNode, $select, $bulk);
    }

    /**
     * Создает объект для чтения даных из файла и обновления существующих записей.
     *
     * @param string $entity    Название сущности фиас, длякоторой создается объект
     * @param string $tableName Название таблицы, в которую будет произведена запись
     * @param string $primary   Название первичного ключа для таблицы
     * @param array  $fields    Массив с соответствием полей, вида "поле в таблице => поле в файле"
     *
     * @return \marvin255\fias\TaskInterface
     */
    public function updater(string $entity, string $tableName, string $primary = null, array $fields = null): TaskInterface
    {
        $entityDescription = $this->getEntityDescription($entity);

        $filePattern = $entityDescription['insertFilePattern'];
        $pathToNode = $entityDescription['xmlPathToNode'];
        $select = $fields ?: $entityDescription['xmlSelect'];
        $primary = $primary ?: $entityDescription['primary'];

        return new UpdateData($tableName, $primary, $filePattern, $pathToNode, $select);
    }

    /**
     * Создает объект для чтения даных из файла и удаления существующих записей.
     *
     * @param string $entity    Название сущности фиас, длякоторой создается объект
     * @param string $tableName Название таблицы, в которую будет произведена запись
     * @param string $primary   Название первичного ключа для таблицы
     * @param array  $fields    Массив с соответствием полей, вида "поле в таблице => поле в файле"
     *
     * @return \marvin255\fias\TaskInterface
     */
    public function deleter(string $entity, string $tableName, string $primary = null, array $fields = null): TaskInterface
    {
        $entityDescription = $this->getEntityDescription($entity);

        $filePattern = $entityDescription['deleteFilePattern'];
        $pathToNode = $entityDescription['xmlPathToNode'];
        $select = $fields ?: $entityDescription['xmlSelect'];
        $primary = $primary ?: $entityDescription['primary'];

        return new DeleteData($tableName, $primary, $filePattern, $pathToNode, $select);
    }

    /**
     * Возвращает описание сущности по ее названию.
     *
     * @param string $name
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function getEntityDescription(string $name): array
    {
        $return = !empty($this->entitiesDescription[$name])
            ? $this->entitiesDescription[$name]
            : null;

        if (!$return) {
            throw new InvalidArgumentException(
                "Can't find description for {$name} entity"
            );
        }

        return $return;
    }
}
