<?php namespace Salxig\Test\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSalxigTestUnits extends Migration
{
    public function up()
    {
        Schema::create('salxig_test_units', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('id_inv_unit')->nullable()->unsigned();
            $table->string('name', 255)->nullable();
            $table->integer('id_unit_type')->nullable()->unsigned();
            $table->integer('id_location')->nullable()->unsigned();
            $table->string('serial_num', 100)->nullable();
            $table->text('note')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('salxig_test_units');
    }
}
