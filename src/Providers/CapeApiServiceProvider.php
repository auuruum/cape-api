<?php

namespace Azuriom\Plugin\CapeApi\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\Permission;
use Azuriom\Plugin\CapeApi\Cards\ChangeCapeViewCard;
use Illuminate\Support\Facades\View;

class CapeApiServiceProvider extends BasePluginServiceProvider
{
    /**
     * Register any plugin services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->registerRouteDescriptions();
        $this->registerAdminNavigation();
        $this->registerUserNavigation();

        Permission::registerPermissions([
            'admin.cape-api' => 'cape-api::admin.permissions.admin',
        ]);

        View::composer('profile.index', ChangeCapeViewCard::class);
    }

    /**
     * Returns the routes that should be able to be added to the navbar.
     *
     * @return array
     */
    protected function routeDescriptions()
    {
        return [
            'cape-api.cape' => trans('cape-api::messages.profile.title'),
        ];
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array
     */
    protected function adminNavigation()
    {
        return [
            'cape-api' => [
                'name' => trans('cape-api::admin.title'),
                'icon' => 'bi bi-gear',
                'route' => 'cape-api.admin.settings',
                'permission' => 'admin.cape-api',
            ],
        ];
    }

    /**
     * Return the user navigations routes to register in the navbar.
     *
     * @return array
     */
    protected function userNavigation()
    {
        // Check if cape navigation is enabled
        if (!setting('cape-api.show_cape', true)) {
            return [];
        }

        $navigation = [
            'cape' => [
                'name' => trans('cape-api::messages.title'),
                'route' => 'cape-api.cape',
                'icon' => ' ',
            ],
        ];

        $icon = setting('cape-api.icon');
        if (!empty($icon)) {
            $navigation['cape']['icon'] = $icon;
        }

        return $navigation;
    }
}
