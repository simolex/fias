<?php namespace Salxig\Getfias\Models;

use Model;

/**
 * Model
 */
class Fias_fileinfo extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'salxig_getfias_fileinfo';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
