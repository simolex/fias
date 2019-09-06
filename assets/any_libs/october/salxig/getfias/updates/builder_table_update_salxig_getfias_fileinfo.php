<?php namespace Salxig\Getfias\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSalxigGetfiasFileinfo extends Migration
{
    public function up()
    {
        Schema::table('salxig_getfias_fileinfo', function($table)
        {
            $table->string('complete_dbf_url', 150)->nullable();
            $table->string('delta_dbf_url', 150)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('salxig_getfias_fileinfo', function($table)
        {
            $table->dropColumn('complete_dbf_url');
            $table->dropColumn('delta_dbf_url');
        });
    }
}
