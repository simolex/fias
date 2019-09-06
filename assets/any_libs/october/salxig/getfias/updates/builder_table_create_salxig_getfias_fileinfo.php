<?php namespace Salxig\Getfias\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSalxigGetfiasFileinfo extends Migration
{
    public function up()
    {
        Schema::create('salxig_getfias_fileinfo', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('ver_id')->unsigned();
            $table->string('text_version', 50)->nullable();
            $table->string('complete_xml_url', 150)->nullable();
            $table->string('delta_xml_url', 150)->nullable();
            $table->boolean('uploaded')->default(0);
            $table->boolean('readed')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary(['ver_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('salxig_getfias_fileinfo');
    }
}
