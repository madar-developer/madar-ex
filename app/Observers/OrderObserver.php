<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class OrderObserver
{
    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if (! $order->wasChanged('driver_id')) {
            return;
        }

        $latestActiveLog = OrderLog::where('order_id', $order->id)
            // ->where('active', 1)
            ->latest()
            ->first();

        $previousDriverId = $order->getOriginal('driver_id');

        // Avoid duplicate log rows when another flow already wrote the same driver change.
        if ($latestActiveLog && $latestActiveLog->details === 'Order driver changed from '.$previousDriverId.' to '.$order->driver_id) {
            return;
        }

        $log = OrderLog::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'details' => 'Order driver changed from '.$previousDriverId.' to '.$order->driver_id,
            'added_by_type' => $this->resolveAddedByType(),
            'added_by_id' => $this->resolveAddedById(),
            'active' => 1,
            'changed_from' => $this->resolveChangedFrom(),
        ]);

        // OrderLog::where('order_id', $order->id)
        //     ->where('id', '<>', $log->id)
        //     ->update(['active' => 0]);

        OrderLog::where('order_id', $order->id)
            ->where('id', $log->id)
            ->delete();
    }

    /**
     * Resolve changed source (HTTP action or console).
     *
     * @return string
     */
    protected function resolveChangedFrom()
    {
        if (app()->runningInConsole()) {
            return 'console';
        }

        $route = Request::route();
        $method = Request::method();
        $uri = Request::path();
        $action = $route ? $route->getActionName() : 'unknown';

        return $method.' '.$uri.' | '.$action;
    }

    /**
     * Resolve actor type from available auth guards.
     *
     * @return string|null
     */
    protected function resolveAddedByType()
    {
        if (Auth::guard('api-driver')->check()) {
            return 'driver';
        }

        if (Auth::guard('admin')->check()) {
            return 'admin';
        }

        if (Auth::guard('api-company')->check()) {
            return 'company';
        }

        if (Auth::guard('api')->check()) {
            return 'user';
        }

        return null;
    }

    /**
     * Resolve actor id from available auth guards.
     *
     * @return int|null
     */
    protected function resolveAddedById()
    {
        $guards = ['api-driver', 'admin', 'api-company', 'api'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->id();
            }
        }

        return null;
    }
}
