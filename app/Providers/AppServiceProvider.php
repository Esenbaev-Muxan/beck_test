<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ProductionService;
use App\Repositories\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ProductionService::class, function ($app) {
            return new ProductionService(new ProductRepository());
        });
    }

    public function boot()
    {
        //
    }
}
