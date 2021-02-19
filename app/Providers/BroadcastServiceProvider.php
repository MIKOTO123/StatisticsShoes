<?php

namespace App\Providers;

use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => ['client']]);

//        \Route::match(
//            ['get', 'post'], '/broadcasting/auth',
//            '\\'.BroadcastController::class.'@authenticate'
//        );
//        Broadcast::auth();
        require base_path('routes/channels.php');
    }
}
