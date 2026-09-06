<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $p_m = new PMResource($this->PaymentMethod()->first());
        if(!$this->PaymentMethod()->first()){
            $p_m = new \stdClass;
            $p_m->id = 0;
            $p_m->name = "no payment method";
        }
        return [
            'id' => $this->id,
            'recipent_name' => $this->recipent_name,
            'phone' => $this->phone,
            'city_id' => $this->city_id,
            'adress_details' => $this->adress_details,
            'payment_method_id' => $this->payment_method_id,
            'packages_number' => $this->packages_number,
            'price' => $this->price,
            'notes' => $this->notes,
            'company_id' => $this->company_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'refrence_no' => $this->refrence_no,
            'serial' => $this->serial,
            'serial_no' => $this->serial_no,
            'driver_id' => $this->driver_id,
            'collected' => $this->collected,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'signature' => $this->signature,
            'description' => $this->description,
            'receive_date' => $this->receive_date,
            'delivery_date' => $this->delivery_date,
            'pick_up_date' => $this->pick_up_date,
            'weight' => $this->weight,
            'status_txt' => $this->status_txt,
            'status_image' => $this->status_image,
            'status_color' => $this->status_color,
            'available_statuses' => $this->available_statuses,
            'company' => new CompanyResource($this->Company()->first()),
            'payment_method' => $p_m,
            'steps' => $this->buildOrderSteps(),
            'images' => $this->Files->map(function ($file) {
                return [
                    'id' => $file->id,
                    'url' => getImage($file->name),
                ];
            })->values(),
            'image_groups' => $this->imageGroups()->map(function ($group) {
                return [
                    'group_id' => $group['group_id'],
                    'status' => $group['status'],
                    'status_txt' => $group['status_txt'],
                    'status_details' => $group['status_details'],
                    'status_color' => $group['status_color'],
                    'created_at' => optional($group['created_at'])->format('Y-m-d H:i:s'),
                    'images' => $group['images'],
                ];
            })->values(),
        ];
    }

    protected function buildOrderSteps()
    {
        $progressSteps = [
            1 => 'جاري تجهيز الشحنة',
            2 => 'تم استلام الطلب من المتجر ',
            5 => 'الشحنه فى مستودع مدار ',
            6 => 'جاري التوصيل ',
            7 => 'تم التسليم',
        ];

        $statusMap = [
            'init' => 1,
            'at_madar' => 5,
            'at_office' => 6,
            'delivered' => 7,
            'deliver_failed' => 6,
            'returned' => 7,
        ];

        $orderStatusIsFailed = $this->status === 'deliver_failed';
        $orderStatusIsReturned = $this->status === 'returned';
        $currentProgress = $statusMap[$this->status] ?? 1;

        $logs = $this->OrderLog()->latest()->get();
        $latestShownLog = null;
        $lastDetails = '';
        foreach ($logs as $log) {
            if ($lastDetails === $log->details) {
                continue;
            }
            $latestShownLog = $log;
            break;
        }

        $lastStatusIsDelivered = ($this->status === 'delivered')
            || ($latestShownLog && $latestShownLog->status === 'delivered');

        $items = [];
        $totalSteps = count($progressSteps);
        $stepNumber = 0;

        foreach ($progressSteps as $index => $label) {
            $stepNumber++;
            $isLast = $stepNumber === $totalSteps;

            if ($orderStatusIsFailed && $isLast) {
                $label = $this->status_txt ?: 'تعذر التسليم';
                $done = true;
                $failed = false;
            } elseif ($orderStatusIsReturned && $isLast) {
                $label = $this->status_txt ?: 'تم ارجاع الطلب للتاجر';
                $done = true;
                $failed = false;
            } else {
                $done = $index <= $currentProgress;
                $failed = false;
            }

            $items[] = [
                'index' => $index,
                'label' => $label,
                'done' => $done,
                'failed' => $failed,
                'delivered_final' => ! $orderStatusIsFailed && ! $orderStatusIsReturned && $isLast && $lastStatusIsDelivered && $index <= $currentProgress,
            ];
        }

        return [
            'current_progress' => $currentProgress,
            'is_failed' => $orderStatusIsFailed,
            'items' => $items,
        ];
    }
}
