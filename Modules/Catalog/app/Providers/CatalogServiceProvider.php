<?php

namespace Modules\Catalog\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\Catalog\Services\InventoryService;
use Modules\Catalog\Services\ProductCatalogService;
use Modules\Core\Contracts\InventoryServiceInterface;
use Modules\Core\Contracts\ProductCatalogInterface;
use Nwidart\Modules\Support\ModuleServiceProvider;

class CatalogServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Catalog';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'catalog';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->bind(ProductCatalogInterface::class, ProductCatalogService::class);
        $this->app->bind(InventoryServiceInterface::class, InventoryService::class);
    }

    /**
     * Define module schedules.
     *
     * @param  $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
