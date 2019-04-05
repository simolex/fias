<?php namespace Salxig\Fias\Models;

use Model;

/**
 * Settings Model
 */
class Settings extends Model
{

    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'salxig_fias_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
