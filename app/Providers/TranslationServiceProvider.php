<?php

namespace App\Providers;

use App\Helpers\TranslationHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register uiTrans as a Blade directive
        Blade::directive('uiTrans', function ($expression) {
            return "<?php echo App\\Helpers\\TranslationHelper::uiTrans({$expression}); ?>";
        });
        
        // Register getGuestSettingValue as a Blade directive
        Blade::directive('guestSetting', function ($expression) {
            return "<?php echo App\\Helpers\\TranslationHelper::getGuestSettingValue({$expression}); ?>";
        });
    }
}