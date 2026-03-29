<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\OrderLog;
use App\Observers\OrderObserver;
use App\Observers\OrderLogObserver;
use App\Services\SaudiAddressService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Factory as FirebaseFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SaudiAddressService::class, function () {
            return new SaudiAddressService();
        });

        $credentialsPath = config('services.firebase.credentials') ?? env('FIREBASE_CREDENTIALS');
        if ($credentialsPath && is_file($credentialsPath)) {
            $this->app->singleton(Messaging::class, function () use ($credentialsPath) {
                return (new FirebaseFactory)
                    ->withServiceAccount($credentialsPath)
                    ->createMessaging();
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Order::observe(OrderObserver::class);
        OrderLog::observe(OrderLogObserver::class);
        if (request()->has('notify') && request()->get('notify') != '') {
            DB::table('notifications')->where('id', request()->get('notify'))->update(['read_at' => Carbon::now()]);
        }
    }
}
