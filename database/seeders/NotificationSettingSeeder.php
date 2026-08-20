<?php

namespace Database\Seeders;

use App\Models\NotificationSetting;
use App\Models\RenewalReminderStage;
use Illuminate\Database\Seeder;

class NotificationSettingSeeder extends Seeder
{
    public function run(): void
    {
        NotificationSetting::updateOrCreate(
            ['type' => 'birthday'],
            [
                'is_enabled' => true,
                'channel_whatsapp' => true,
                'channel_sms' => false,
                'channel_email' => false,
                'subject' => 'Happy Birthday from Qafila Insurance!',
                'body' => "Happy Birthday, {{customer_name}}! 🎉\n\n"
                    ."The entire team at {{company_name}} wishes you a wonderful year ahead. "
                    ."As a valued customer, don't forget your Qafila Loyalty benefits are ready whenever you need them.\n\n"
                    ."Need help with your policy? We're always here for you.",
            ]
        );

        NotificationSetting::updateOrCreate(
            ['type' => 'renewal'],
            [
                'is_enabled' => true,
                'channel_whatsapp' => true,
                'channel_sms' => false,
                'channel_email' => false,
                'subject' => 'Your policy is expiring soon — renew with Qafila',
                'body' => "Hello {{customer_name}},\n\n"
                    ."Your {{insurance_type}} policy ({{policy_number}}) with {{company_name}} expires on {{policy_expiry_date}} — that's {{days_remaining}} day(s) from now.\n\n"
                    ."Renew today to stay protected without interruption: {{renewal_link}}\n\n"
                    .'Reply to this message or contact us if you have any questions.',
                'renewal_payment_link' => 'https://qafilainsurance.com/renew',
            ]
        );

        foreach ([90, 30, 14, 7, 4, 3, 2, 1] as $days) {
            RenewalReminderStage::updateOrCreate(['days_before' => $days], ['is_enabled' => true]);
        }
    }
}
