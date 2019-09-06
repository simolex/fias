<?php namespace Salxig\Getfias\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSalxigGetfiasFileinfo2 extends Migration
{
    public function up()
    {
        Schema::table('salxig_getfias_fileinfo', function($table)
        {
            $table->boolean('skipped')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('salxig_getfias_fileinfo', function($table)
        {
            $table->dropColumn('skipped');
        });
    }
}
