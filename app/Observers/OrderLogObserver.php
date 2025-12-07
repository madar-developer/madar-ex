<?php

namespace App\Observers;

use App\Models\OrderLog;

class OrderLogObserver
{
    /**
     * Handle the order log "created" event.
     *
     * @param  \App\OrderLog  $orderLog
     * @return void
     */
    public function created(OrderLog $orderLog)
    {
        OrderLog::where('order_id', $orderLog->order_id)
        ->where('id', '<>', $orderLog->id)
        ->update(['active' => '0']);
    }

    /**
     * Handle the order log "updated" event.
     *
     * @param  \App\OrderLog  $orderLog
     * @return void
     */
    public function updated(OrderLog $orderLog)
    {
        //
    }

    /**
     * Handle the order log "deleted" event.
     *
     * @param  \App\OrderLog  $orderLog
     * @return void
     */
    public function deleted(OrderLog $orderLog)
    {
        //
    }

    /**
     * Handle the order log "restored" event.
     *
     * @param  \App\OrderLog  $orderLog
     * @return void
     */
    public function restored(OrderLog $orderLog)
    {
        //
    }

    /**
     * Handle the order log "force deleted" event.
     *
     * @param  \App\OrderLog  $orderLog
     * @return void
     */
    public function forceDeleted(OrderLog $orderLog)
    {
        //
    }
}
