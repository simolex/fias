<?php namespace Salxig\Fias\Models;

use Model;

/**
 * Fileinfo Model
 */
class Fileinfo extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'salxig_fias_fileinfos';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
