<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Compatibilidade com MySQL 5.7 e utf8mb4
        Schema::defaultStringLength(191);
    }
}
