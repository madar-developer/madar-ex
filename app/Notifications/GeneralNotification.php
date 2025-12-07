<?php

namespace App\Notifications;

use App\Http\Controllers\Api\FCMController;
use App\Models\Order;
use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;
use Pusher\Pusher;

class GeneralNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $message;
    private $redirect;
    public function __construct($message, $redirect)
    {
        $this->message = $message;
        $this->redirect = $redirect;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data['message'] = $this->message;
        $data['link'] = $this->redirect;
        $data['date'] = 'الان';
        $options = array(
            'cluster' => 'eu',
            'useTLS' => true
        );
        $pusher = new Pusher(
                        '102a762d9098926ae234',
                        '79d3e93cd3741f7bfe18',
                        '1062023',
                        $options
                    );
        // company/company-orders
            $related_id = null;
            $type = null;
        if (strpos($this->redirect, 'company/company-orders') !== false) {
            $order_id = substr($this->redirect, 24);
            $data2 = [
                'order_id' => $order_id,
                'title_ar' => '#'.$order_id,
                'title_en' => '#'.$order_id,
                'content_ar' => $this->message,
                'content_en' => $this->message,
                'type' => 'order_details',
            ];
            $related_id = $order_id;
            $type = 'order_details';
            $order = Order::find($order_id);
            $company = $order->Company()->first();
            $token = $company->PlayerId()->pluck('player_id')->toArray();
            FCMController::Push('#'.$order_id, $this->message,$token,$data2, 'order_details');
        }
        if (strpos($this->redirect, 'company/company-transfers') !== false) {
            $transfer_id = substr($this->redirect, 27);
            // dd($transfer_id);
            $data2 = [
                'transfer_id' => $transfer_id,
                'title_ar' => 'حوالة رقم '.$transfer_id,
                'title_en' => 'حوالة رقم '.$transfer_id,
                'content_ar' => $this->message,
                'content_en' => $this->message,
                'type' => 'transfer_details',
            ];
            $related_id = $transfer_id;
            $type = 'transfer_details';
            $transfer = Transfer::find($transfer_id);
            $company = $transfer->Company()->first();
            $token = $company->PlayerId()->pluck('player_id')->toArray();
            FCMController::Push('حوالة رقم '.$transfer_id, $this->message,$token,$data2, 'transfer_details');
        }

        $pusher->trigger('my-channel-'.$notifiable->id, 'general', $data);
        return [
            'text' => $this->message,
            'related_id' => $related_id,
            'type' => $type,
            'redirect' => $this->redirect,
        ];
    }
}
