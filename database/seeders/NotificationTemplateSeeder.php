<?php

namespace Database\Seeders;

use App\Support\NotificationMessage;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        NotificationMessage::syncDefaults();
    }
}
