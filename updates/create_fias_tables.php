<?php namespace Salxig\Fias\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateFiasTables extends Migration
{
    public function up()
    {
        //Классификатор адресообразующих элементов
        //AddressObjects
        Schema::create('salxig_fias_adr_objects', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->uuid('aoguid');            // Глобальный уникальный идентификатор адресного объекта
            $table->string('formalname', 120); // Формализованное наименование
            $table->string('regioncode', 2);   // Код региона
            $table->string('autocode', 1);     // Код автономии
            $table->string('areacode', 3);     // Код района
            $table->string('citycode', 3);     // Код города
            $table->string('ctarcode', 3);     // Код внутригородского района
            $table->string('placecode', 3);    // Код населенного пункта
            $table->string('plancode', 4);     // Код элемента планировочной структуры
            $table->string('streetcode', 4)    // Код улицы
                    ->nullable();
            $table->string('extrcode', 4);     // Код дополнительного адресообразующего элемента
            $table->string('sextcode', 3);     // Код подчиненного дополнительного адресообразующего элемента
            $table->string('offname', 120)     // Официальное наименование
                    ->nullable();
            $table->string('postalcode', 6)    // Почтовый индекс
                    ->nullable();
            $table->string('ifnsfl', 4)        // Код ИФНС ФЛ
                    ->nullable();
            $table->string('terrifnsfl', 4)    // Код территориального участка ИФНС ФЛ
                    ->nullable();
            $table->string('ifnsul', 4)        // Код ИФНС ЮЛ
                    ->nullable();
            $table->string('terrifnsul', 4)    // Код территориального участка ИФНС ЮЛ
                    ->nullable();
            $table->string('okato', 11)        // OKATO
                    ->nullable();
            $table->string('oktmo', 11)        // OKTMO
                    ->nullable();
            $table->date('updatedate');        // Дата  внесения записи
            $table->string('shortname', 10);   // Краткое наименование типа объекта
            $table->tinyInteger('aolevel')     // Уровень адресного объекта
                    ->unsigned();
            $table->uuid('parentguid')         // Идентификатор объекта родительского объекта
                    ->nullable();
            $table->uuid('aoid');              // Уникальный идентификатор записи. Ключевое поле.
            $table->uuid('previd');            // Идентификатор записи связывания с предыдушей исторической записью
                    ->nullable();
            $table->uuid('nextid');            // Идентификатор записи  связывания с последующей исторической записью
                    ->nullable();
            $table->string('code', 17);        // Код адресного объекта одной строкой с признаком актуальности из КЛАДР 4.0.
                    ->nullable();
            $table->string('plaincode', 15);   // Код адресного объекта из КЛАДР 4.0 одной строкой без признака актуальности (последних двух цифр)
                    ->nullable();
            $table->tinyInteger('actstatus');  // Статус актуальности адресного объекта ФИАС. Актуальный адрес на текущую дату. Обычно последняя запись об адресном объекте.
                    ->unsigned();              //       0 – Не актуальный
                                               //       1 - Актуальный
            $table->tinyInteger('centstatus'); // Статус центра
                    ->unsigned();
            $table->tinyInteger('operstatus'); // Статус действия над записью – причина появления записи (см. описание таблицы OperationStatus):
                    ->unsigned();              //       01 – Инициация;
                                               //       10 – Добавление;
                                               //       20 – Изменение;
                                               //       21 – Групповое изменение;
                                               //       30 – Удаление;
                                               //       31 - Удаление вследствие удаления вышестоящего объекта;
                                               //       40 – Присоединение адресного объекта (слияние);
                                               //       41 – Переподчинение вследствие слияния вышестоящего объекта;
                                               //       42 - Прекращение существования вследствие присоединения к другому адресному объекту;
                                               //       43 - Создание нового адресного объекта в результате слияния адресных объектов;
                                               //       50 – Переподчинение;
                                               //       51 – Переподчинение вследствие переподчинения вышестоящего объекта;
                                               //       60 – Прекращение существования вследствие дробления;
                                               //       61 – Создание нового адресного объекта в результате дробления
            $table->tinyInteger('currstatus'); // Статус актуальности КЛАДР 4
                    ->unsigned();              //   (последние две цифры в коде)
            $table->date('startdate');         // Начало действия записи
            $table->date('enddate');           // Окончание действия записи
            $table->uuid('normdoc');           // Внешний ключ на нормативный документ
            $table->boolean('livestatus');     // Признак действующего адресного объекта
            $table->tinyInteger('divtype');    // Тип адресации:
                    ->unsigned();              //       0 - не определено
                                               //       1 - муниципальный;
                                               //       2 - административно-территориальный
           // $table->string('cadnum');
            $table->primary(['aoid']);
        });

        //ведения по номерам домов улиц городов и населенных пунктов
        //House
        Schema::create('salxig_fias_houses', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->string('postalcode', 6)    // Почтовый индекс
                    ->nullable();
            $table->string('ifnsfl', 4)        // Код ИФНС ФЛ
                    ->nullable();
            $table->string('terrifnsfl', 4)    // Код территориального участка ИФНС ФЛ
                    ->nullable();
            $table->string('ifnsul', 4)        // Код ИФНС ЮЛ
                    ->nullable();
            $table->string('terrifnsul', 4)    // Код территориального участка ИФНС ЮЛ
                    ->nullable();
            $table->string('okato', 11)        // OKATO
                    ->nullable();
            $table->string('oktmo', 11)        // OKTMO
                    ->nullable();
            $table->date('updatedate');        // Дата время внесения записи
            $table->string('housenum', 20)     // Номер дома
                    ->nullable();
            $table->tinyInteger('eststatus')   // Признак владения
                    ->unsigned();
            $table->string('buildnum', 10)     // Номер корпуса
                    ->nullable();
            $table->string('strucnum', 10)     // Номер строения
                    ->nullable();
            $table->tinyInteger('strstatus')   // Признак строения
                    ->unsigned()
                    ->nullable();
            $table->uuid('houseid');           // Уникальный идентификатор записи дома
            $table->uuid('houseguid');         // Глобальный уникальный идентификатор дома
            $table->uuid('aoguid');            // Guid записи родительского объекта (улицы, города, населенного пункта и т.п.)
            $table->date('startdate');         // Начало действия записи
            $table->date('enddate');           // Окончание действия записи
            $table->tinyInteger('statstatus')  // Состояние дома
                    ->unsigned();
            $table->uuid('normdoc')            // Внешний ключ на нормативный документ
                    ->nullable();
            $table->integer('counter')         // Счетчик записей домов для КЛАДР 4
                    ->unsigned();
            $table->string('cadnum', 100)      // Кадастровый номер
                    ->nullable();
            $table->tinyInteger('divtype');    // Тип адресации:
                    ->unsigned();              //       0 - не определено
                                               //       1 - муниципальный;
                                               //       2 - административно-территориальный
            $table->primary(['houseid']);
        });

        // Классификатор земельных участков
        // Stead
        Schema::create('salxig_fias_steads', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->uuid('steadguid');         // Глобальный уникальный идентификатор адресного объекта (земельного участка)
            $table->string('number', 120)      // Номер земельного участка
                    ->nullable();
            $table->string('regioncode', 2);   // Код региона
            $table->string('postalcode', 6)    // Почтовый индекс
                    ->nullable();
            $table->string('ifnsfl', 4)        // Код ИФНС ФЛ
                    ->nullable();
            $table->string('terrifnsfl', 4)    // Код территориального участка ИФНС ФЛ
                    ->nullable();
            $table->string('ifnsul', 4)        // Код ИФНС ЮЛ
                    ->nullable();
            $table->string('terrifnsul', 4)    // Код территориального участка ИФНС ЮЛ
                    ->nullable();
            $table->string('okato', 11)        // OKATO
                    ->nullable();
            $table->string('oktmo', 11)        // OKTMO
                    ->nullable();
            $table->date('updatedate');        // Дата внесения записи
            $table->uuid('parentguid')         // Идентификатор объекта родительского объекта
                    ->nullable();
            $table->string('steadid');         // Уникальный идентификатор записи. Ключевое поле.
            $table->uuid('previd')             //Идентификатор записи связывания с предыдушей исторической записью
                    ->nullable();
            $table->uuid('nextid')             //Идентификатор записи  связывания с последующей исторической записью
                    ->nullable();
            $table->tinyInteger('operstatus'); // Статус действия над записью – причина появления записи (см. описание таблицы OperationStatus):
                    ->unsigned();              //       01 – Инициация;
                                               //       10 – Добавление;
                                               //       20 – Изменение;
                                               //       21 – Групповое изменение;
                                               //       30 – Удаление;
                                               //       31 - Удаление вследствие удаления вышестоящего объекта;
                                               //       40 – Присоединение адресного объекта (слияние);
                                               //       41 – Переподчинение вследствие слияния вышестоящего объекта;
                                               //       42 - Прекращение существования вследствие присоединения к другому адресному объекту;
                                               //       43 - Создание нового адресного объекта в результате слияния адресных объектов;
                                               //       50 – Переподчинение;
                                               //       51 – Переподчинение вследствие переподчинения вышестоящего объекта;
                                               //       60 – Прекращение существования вследствие дробления;
                                               //       61 – Создание нового адресного объекта в результате дробления
            $table->date('startdate');         // Начало действия записи
            $table->date('enddate');           // Окончание действия записи
            $table->uuid('normdoc')            // Внешний ключ на нормативный документ
                    ->nullable();
            $table->boolean('livestatus');     // Признак действующего адресного объекта
            $table->string('cadnum', 100)      // Кадастровый номер
                    ->nullable();
            $table->tinyInteger('divtype');    // Тип адресации:
                    ->unsigned();              //       0 - не определено
                                               //       1 - муниципальный;
                                               //       2 - административно-территориальный
            $table->primary(['steadid']);
        });

        // Классификатор помещениях
        // Room
        Schema::create('salxig_fias_rooms', function(Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->string('roomguid');        // Глобальный уникальный идентификатор адресного объекта (помещения)
            $table->string('flatnumber', 50);  // Номер помещения или офиса
            $table->tinyInteger('flattype')    //Тип помещения
                    ->unsigned();
            $table->string('roomnumber', 50)   // Номер комнаты
                    ->nullable();
            $table->tinyInteger('roomtype')    // Тип комнаты
                    ->unsigned()
                    ->nullable()
            $table->string('regioncode', 2);   // Код региона
            $table->string('postalcode', 6)    // Почтовый индекс
                    ->nullable();
            $table->date('updatedate');        // Дата  внесения записи
            $table->uuid('houseguid');         // Идентификатор родительского объекта (дома)
            $table->uuid('roomid');            // Уникальный идентификатор записи. Ключевое поле.
            $table->uuid('previd')             //Идентификатор записи связывания с предыдушей исторической записью
                    ->nullable();
            $table->uuid('nextid')             //Идентификатор записи  связывания с последующей исторической записью
                    ->nullable();
            $table->date('startdate');         // Начало действия записи
            $table->date('enddate');           // Окончание действия записи
            $table->boolean('livestatus');     // Признак действующего адресного объекта
            $table->uuid('normdoc')            // Внешний ключ на нормативный документ
                    ->nullable();
            $table->tinyInteger('operstatus'); // Статус действия над записью – причина появления записи (см. описание таблицы OperationStatus):
                    ->unsigned();              //       01 – Инициация;
                                               //       10 – Добавление;
                                               //       20 – Изменение;
                                               //       21 – Групповое изменение;
                                               //       30 – Удаление;
                                               //       31 - Удаление вследствие удаления вышестоящего объекта;
                                               //       40 – Присоединение адресного объекта (слияние);
                                               //       41 – Переподчинение вследствие слияния вышестоящего объекта;
                                               //       42 - Прекращение существования вследствие присоединения к другому адресному объекту;
                                               //       43 - Создание нового адресного объекта в результате слияния адресных объектов;
                                               //       50 – Переподчинение;
                                               //       51 – Переподчинение вследствие переподчинения вышестоящего объекта;
                                               //       60 – Прекращение существования вследствие дробления;
                                               //       61 – Создание нового адресного объекта в результате дробления
            $table->string('cadnum', 100)      // Кадастровый номер
                    ->nullable();
            $table->string('roomcadnum', 100)  // Кадастровый номер комнаты в помещении
                    ->nullable();

            $table->primary(['roomid']);
        });

        // Сведения по нормативному документу, являющемуся основанием присвоения адресному элементу наименования
        // NormativeDocument
        Schema::create('salxig_fias_norm_docs', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->uuid('normdocid');         // Идентификатор нормативного документа
            $table->text('docname')            // Наименование документа
                    ->nullable();
            $table->date('docdate')            // Дата документа
                    ->nullable();
            $table->string('docnum', 20)       // Номер документа
                    ->nullable();
            $table->tinyInteger('doctype')     // Тип документа
                    ->unsigned();
            $table->uuid('docimgid')           // Идентификатор образа (внешний ключ)
                    ->nullable();

            $table->primary(['normdocid']);
        });

        // Тип адресного объекта
        // AddressObjectType

        Schema::create('salxig_fias_ao_types', function(Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->tinyInteger('level');      // Уровень адресного объекта
            $table->string('scname', 10);      // Краткое наименование типа объекта
                    ->nullable();
            $table->string('socrname', 50);    // Полное наименование типа объекта
            $table->string('kod_t_st', 4);     // Ключевое поле

            $table->primary(['kod_t_st']);
        });

        // Статус актуальности КЛАДР 4.0
        // CurrentStatus
        Schema::create('salxig_fias_cur_statuses', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->tinyInteger('curentstid'); // Идентификатор статуса (ключ)
                    ->unsigned();
            $table->string('name', 100);       // Наименование (0 - актуальный,
                                               //       1-50, 52-98 – исторический,
                                               //       51 - переподчиненный,
                                               //       99 - несуществующий)
            $table->primary(['curentstid']);
        });

        // Статус актуальности ФИАС
        // ActualStatus
        Schema::create('salxig_fias_act_statuses', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->tinyInteger('actstatid');  // Идентификатор статуса (ключ)
                    ->unsigned();
            $table->string('name', 100);       // Наименование
                                               //       0 – Не актуальный
                                               //       1 – Актуальный (последняя запись по адресному объекту)
            $table->primary(['actstatid']);
        });

        // Статус действия
        // OperationStatus
        Schema::create('salxig_fias_oper_statuses', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->tinyInteger('operstatid');  // Идентификатор статуса (ключ)
                    ->unsigned();
            $table->string('name', 100);       // Наименование
                                               //       01 – Инициация;
                                               //       10 – Добавление;
                                               //       20 – Изменение;
                                               //       21 – Групповое изменение;
                                               //       30 – Удаление;
                                               //       31 - Удаление вследствие удаления вышестоящего объекта;
                                               //       40 – Присоединение адресного объекта (слияние);
                                               //       41 – Переподчинение вследствие слияния вышестоящего объекта;
                                               //       42 - Прекращение существования вследствие присоединения к другому адресному объекту;
                                               //       43 - Создание нового адресного объекта в результате слияния адресных объектов;
                                               //       50 – Переподчинение;
                                               //       51 – Переподчинение вследствие переподчинения вышестоящего объекта;
                                               //       60 – Прекращение существования вследствие дробления;
                                               //       61 – Создание нового адресного объекта в результате дробления;
                                               //       70 – Восстановление объекта прекратившего существование

            $table->primary(['operstatid']);
        });

        // Статус центра
        // CenterStatus
        Schema::create('salxig_fias_cnt_statuses', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->tinyInteger('centerstid'); // Идентификатор статуса (ключ)
                    ->unsigned();
            $table->string('name', 100);       // Наименование

            $table->primary(['centerstid']);
        });

        // Статус интервала домов
        // IntervalStatus
        Schema::create('salxig_fias_intv_statuses', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->tinyInteger('intvstatid'); // Идентификатор статуса (обычный, четный, нечетный)
                    ->unsigned();
            $table->string('name', 60);        // Наименование

            $table->primary(['intvstatid']);
        });

        // Статус состояния домов
        // HouseStateStatus
        Schema::create('salxig_fias_hst_statuses', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->tinyInteger('housestid');  // Идентификатор статуса
                    ->unsigned();
            $table->string('name', 60);        // Наименование

            $table->primary(['housestid']);
        });

        // Признак владения
        // EstateStatus
        Schema::create('salxig_fias_est_statuses', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->tinyInteger('eststatid');  // Признак владения
                    ->unsigned();
            $table->string('name', 60);        // Наименование
            $table->string('shortname', 20)    // Краткое наименование
                    ->nullable();

            $table->primary(['eststatid']);
        });

        // Признак строения
        // StructureStatuses
        Schema::create('salxig_fias_str_statuses', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->tinyInteger('strstatid')       // Признак строения
                    ->unsigned();
            $table->string('name', 20);        // Наименование
            $table->string('shortname', 20)    // Краткое наименование
                    ->nullable();

            $table->primary(['strstatid']);
        });

        // Тип помещения
        // FlatType
        Schema::create('salxig_fias_fl_types', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->tinyInteger('fltypeid')       // Признак строения
                    ->unsigned();
            $table->string('name', 20);        // Наименование
            $table->string('shortname', 20)    // Краткое наименование
                    ->nullable();

            $table->primary(['fltypeid']);
        });

        // Тип нормативного документа
        // NormativeDocumentType
        Schema::create('salxig_fias_ndoc_types', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->smallInteger('ndtypeid');  // Идентификатор записи (ключ)
                    ->unsigned();
            $table->string('name', 250);       // Наименование типа нормативного документа

            $table->primary(['ndtypeid']);
        });

        // Тип комнаты
        // RoomType
        Schema::create('salxig_fias_room_types', function(Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->tinyInteger('rmtypeid')    // Тип комнаты
                    ->unsigned();
            $table->string('name', 20);        // Наименование
            $table->string('shortname', 20)    // Краткое наименование
                    ->nullable();

            $table->primary(['rmtypeid']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('salxig_fias_adr_objects');
        Schema::dropIfExists('salxig_fias_houses');
        Schema::dropIfExists('salxig_fias_steads');
        Schema::dropIfExists('salxig_fias_rooms');
        Schema::dropIfExists('salxig_fias_norm_docs');
        Schema::dropIfExists('salxig_fias_ao_types');
        Schema::dropIfExists('salxig_fias_cur_statuses');
        Schema::dropIfExists('salxig_fias_act_statuses');
        Schema::dropIfExists('salxig_fias_oper_statuses');
        Schema::dropIfExists('salxig_fias_cnt_statuses');
        Schema::dropIfExists('salxig_fias_intv_statuses');
        Schema::dropIfExists('salxig_fias_hst_statuses');
        Schema::dropIfExists('salxig_fias_est_statuses');
        Schema::dropIfExists('salxig_fias_str_statuses');
        Schema::dropIfExists('salxig_fias_fl_types');
        Schema::dropIfExists('salxig_fias_ndoc_types');
        Schema::dropIfExists('salxig_fias_room_types');
    }
}