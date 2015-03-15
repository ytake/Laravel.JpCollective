<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ConfigServiceProvider
 * @package App\Providers
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class ConfigServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function register()
    {
        if ($this->app->environment("local")) {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
        }

        config([
            //
        ]);
    }

}
