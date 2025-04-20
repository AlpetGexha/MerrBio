<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FluxServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Flux components
        $this->loadViewsFrom(__DIR__.'/../../resources/views/flux', 'flux');

        // Register component aliases
        $this->registerComponentAliases();
    }

    /**
     * Register component aliases.
     */
    protected function registerComponentAliases(): void
    {
        $components = [
            'button' => 'components.button',
            'input' => 'components.input',
            'select' => 'components.select',
            'toggle' => 'components.toggle',
            'radio' => 'components.radio',
            'icon' => 'components.icon',
        ];

        foreach ($components as $alias => $component) {
            Blade::component("flux::{$component}", "flux::{$alias}");
        }
    }
}
