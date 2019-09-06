<?php namespace Salxig\Test\Models;

use Model;

/**
 * Model
 */
class Unit extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'salxig_test_units';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
