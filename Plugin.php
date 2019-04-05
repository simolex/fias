<?php namespace Salxig\Fias;
use App;
use Config;
use Backend;
use System\Classes\PluginBase;
use Illuminate\Foundation\AliasLoader;
use System\Classes\SettingsManager;


/**
 * fias Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'fias',
            'description' => 'Plugin  get information from fias.nalog.ru',
            'author'      => 'salxig',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConsoleCommand('fias.database', 'Salxig\Fias\Console\FiasDatabase');

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        $this->bootPackages();
    }

    /**
     * Boots (configures and registers) any packages found within this plugin's packages.load configuration value
     *
     * @see https://luketowers.ca/blog/how-to-use-laravel-packages-in-october-plugins
     * @author Luke Towers <octobercms@luketowers.ca>
     */
    public function bootPackages()
    {
        // Get the namespace of the current plugin to use in accessing the Config of the plugin
        $pluginNamespace = str_replace('\\', '.', strtolower(__NAMESPACE__));


        // Instantiate the AliasLoader for any aliases that will be loaded
        $aliasLoader = AliasLoader::getInstance();

        // Get the packages to boot
        $packages = Config::get($pluginNamespace . '::packages');

        // Boot each package
        foreach ($packages as $name => $options) {
            // Setup the configuration for the package, pulling from this plugin's config
            if (!empty($options['config']) && !empty($options['config_namespace'])) {
                Config::set($options['config_namespace'], $options['config']);
            }

            // Register any Service Providers for the package
            if (!empty($options['providers'])) {
                foreach ($options['providers'] as $provider) {
                    App::register($provider);
                }
            }

            // Register any Aliases for the package
            if (!empty($options['aliases'])) {
                foreach ($options['aliases'] as $alias => $path) {
                    $aliasLoader->alias($alias, $path);
                }
            }
        }
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'Salxig\Fias\Components\Weather' => 'weather',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'salxig.fias.some_permission' => [
                'tab' => 'fias',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {

        //return []; // Remove this line to activate

        return [
            'fias' => [
                'label'       => 'fias_',
                'url'         => Backend::url('salxig/fias/fileinfo'),
                'icon'        => 'icon-leaf',
               // 'permissions' => ['salxig.fias.*'],
                'order'       => 500,
            ],
        ];
    }


    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Fias Settings',
                'description' => 'Set FIAS Plugin settings',
                'category'    => SettingsManager::CATEGORY_MYSETTINGS,
                'icon'        => 'icon-cog',
                'class'       => 'Salxig\Fias\Models\Settings',
                'order'       => 500//,
                //'permissions' => ['salxig.fias.access_settings']
            ]
        ];
    }


}
