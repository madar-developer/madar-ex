<?php

namespace App\Providers;

use App\Models\OrderLog;
use App\Observers\OrderLogObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        OrderLog::observe(OrderLogObserver::class);
        if (Request()->has('notify') && Request()->get('notify') != '') {
            DB::table('notifications')->where('id', Request()->get('notify'))->update(['read_at' => Carbon::now()]);
        }
    }
}
