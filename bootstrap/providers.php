<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    Modules\Core\App\Providers\VariablesStateServiceProvider::class,
    Modules\Core\App\Providers\DynamicMenuServiceProvider::class,
    Modules\Core\App\Providers\ModuleViewFallbackProvider::class,
    Modules\Core\App\Providers\ViewServiceProvider::class
];
