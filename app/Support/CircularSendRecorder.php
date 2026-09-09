<?php

namespace App\Support;

use App\Models\Admin;
use App\Models\CircularSend;
use App\Models\CircularSendRecipient;
use App\Models\Company;
use App\Models\Driver;
use Illuminate\Support\Collection;

class CircularSendRecorder
{
    public static function record(
        string $title,
        ?string $description,
        iterable $companies = [],
        iterable $drivers = [],
        iterable $admins = [],
        array $flags = [],
        string $source = CircularSend::SOURCE_NOTIFICATION
    ): CircularSend {
        $companies = Collection::make($companies);
        $drivers = Collection::make($drivers);
        $admins = Collection::make($admins);

        $send = CircularSend::create([
            'admin_id' => auth('admin')->id(),
            'title' => $title,
            'description' => $description,
            'source' => $source,
            'sent_to_all_companies' => (bool) ($flags['all_companies'] ?? false),
            'sent_to_all_drivers' => (bool) ($flags['all_drivers'] ?? false),
            'sent_to_all_admins' => (bool) ($flags['all_admins'] ?? false),
        ]);

        $rows = [];
        $now = now();

        foreach ($companies as $company) {
            $rows[] = [
                'circular_send_id' => $send->id,
                'recipient_type' => CircularSendRecipient::TYPE_COMPANY,
                'recipient_id' => $company->id,
                'recipient_name' => $company->name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach ($drivers as $driver) {
            $rows[] = [
                'circular_send_id' => $send->id,
                'recipient_type' => CircularSendRecipient::TYPE_DRIVER,
                'recipient_id' => $driver->id,
                'recipient_name' => trim(($driver->first_name ?? '').' '.($driver->last_name ?? '')),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach ($admins as $admin) {
            $rows[] = [
                'circular_send_id' => $send->id,
                'recipient_type' => CircularSendRecipient::TYPE_ADMIN,
                'recipient_id' => $admin->id,
                'recipient_name' => $admin->name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows !== []) {
            foreach (array_chunk($rows, 500) as $chunk) {
                CircularSendRecipient::insert($chunk);
            }
        }

        return $send;
    }

    public static function forCircularType(string $type, string $title, ?string $description): CircularSend
    {
        $companies = collect();
        $drivers = collect();
        $admins = collect();
        $flags = [];

        if ($type === \App\Models\Circular::TYPE_COMPANY) {
            $companies = Company::query()->get(['id', 'name']);
            $flags['all_companies'] = true;
        } elseif ($type === \App\Models\Circular::TYPE_DRIVER) {
            $drivers = Driver::query()->get(['id', 'first_name', 'last_name']);
            $flags['all_drivers'] = true;
        } else {
            $admins = Admin::query()->get(['id', 'name']);
            $flags['all_admins'] = true;
        }

        return self::record(
            $title,
            $description,
            $companies,
            $drivers,
            $admins,
            $flags,
            CircularSend::SOURCE_CIRCULAR
        );
    }
}
