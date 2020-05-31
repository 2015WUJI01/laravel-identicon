<?php

namespace Wuchienkun\Identicon\Providers;

use Wuchienkun\Identicon\Identicon;
use Wuchienkun\Identicon\Generator\GdGenerator;
use Wuchienkun\Identicon\Generator\SvgGenerator;
use Wuchienkun\Identicon\Generator\ImageMagickGenerator;
use Illuminate\Support\ServiceProvider;

class IdenticonServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('identicon', function () {
            switch (config('identicon.generator')) {
                case 'imagick':
                    $identicon = new Identicon(new ImageMagickGenerator());
                    break;
                case 'gd':
                    $identicon = new Identicon(new GdGenerator());
                    break;
                case 'svg':
                default:
                    $identicon = new Identicon(new SvgGenerator());
                    break;
            }
            return $identicon;
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/identicon.php' => config_path('identicon.php'),
        ], 'laravel-identicon');
    }
}
