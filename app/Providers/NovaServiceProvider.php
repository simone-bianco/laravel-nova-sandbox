<?php

namespace App\Providers;

use App\Nova\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::initialPath('/resources/orders');

        Nova::userLocale(function () {
            return match (app()->getLocale()) {
                'it' => 'it-IT',
                default => null,
            };
        });

        Nova::withoutGlobalSearch();

        Nova::mainMenu(function (Request $request, Menu $menu) {
            $menus = [];
            $defaultMenus = [];

            $defaultMenus[] = MenuItem::resource(User::class)->name(__('Users'));
            $menus[] = MenuSection::make('Menu', $defaultMenus)->collapsable();

            $entitiesMenu = [];
            $menus[] = MenuSection::make(__('Entities'), $entitiesMenu)->collapsable();

            $managementMenus = [];
            $menus[] = MenuSection::make(__('Management'), $managementMenus)->collapsable();

            $extraMenus = [];
            $menus[] = MenuSection::make(__('Extra'), $extraMenus)->collapsable();

            return $menus;
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
