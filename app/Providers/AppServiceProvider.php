<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\TraitsFolder\BladeDirectives;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
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
        Paginator::useBootstrap();

        Blade::directive('money', function ($amount) {
            return "<?php echo '₹ ' . number_format($amount, 2); ?>";
        });

        Blade::directive('number_decimal', function ($value) {
            return "<?php echo number_format($value, 2); ?>";
        });

        Blade::directive('number', function ($value) {
            return "<?php echo number_format($value); ?>";
        });
    }
}
