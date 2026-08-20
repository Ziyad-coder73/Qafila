<?php

namespace App\Console\Commands;

use App\Models\NotificationLog;
use App\Models\NotificationSetting;
use App\Models\Policy;
use App\Models\RenewalReminderStage;
use App\Models\SiteSetting;
use App\Services\NotificationDispatcher;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('qafila:send-notifications')]
#[Description('Send automatic birthday greetings and Motor/Medical policy renewal reminders.')]
class SendQafilaNotifications extends Command
{
    public function handle(NotificationDispatcher $dispatcher)
    {
        $companyName = SiteSetting::get('company_name', 'Qafila Insurance');

        $birthdayCount = $this->sendBirthdayGreetings($dispatcher, $companyName);
        $renewalCount = $this->sendRenewalReminders($dispatcher, $companyName);

        $this->info("Birthday greetings sent: {$birthdayCount}");
        $this->info("Renewal reminders sent: {$renewalCount}");
    }

    private function sendBirthdayGreetings(NotificationDispatcher $dispatcher, string $companyName): int
    {
        $setting = NotificationSetting::where('type', 'birthday')->first();

        if (! $setting || ! $setting->is_enabled) {
            return 0;
        }

        $today = now();
        $sent = 0;

        $policies = Policy::whereNotNull('birthday')->get()
            ->filter(fn ($policy) => $policy->birthday->format('m-d') === $today->format('m-d'));

        foreach ($policies as $policy) {
            $alreadySent = NotificationLog::where('policy_id', $policy->id)
                ->where('notification_type', 'birthday')
                ->whereYear('sent_at', $today->year)
                ->exists();

            if ($alreadySent) {
                continue;
            }

            $body = $this->renderTemplate($setting->body, $policy, $companyName);

            foreach (array_keys($setting->enabledChannels()) as $channel) {
                $status = $dispatcher->dispatch($channel, $policy->contact_number, $setting->subject, $body);

                NotificationLog::create([
                    'policy_id' => $policy->id,
                    'customer_name' => $policy->customer_name,
                    'contact' => $policy->contact_number,
                    'policy_number' => $policy->policy_number,
                    'insurance_type' => $policy->insuranceType?->name,
                    'notification_type' => 'birthday',
                    'reminder_stage' => null,
                    'channel' => $channel,
                    'status' => $status,
                    'message' => $body,
                    'sent_by' => null,
                    'sent_at' => now(),
                ]);

                $sent++;
            }
        }

        return $sent;
    }

    private function sendRenewalReminders(NotificationDispatcher $dispatcher, string $companyName): int
    {
        $setting = NotificationSetting::where('type', 'renewal')->first();

        if (! $setting || ! $setting->is_enabled) {
            return 0;
        }

        $stages = RenewalReminderStage::where('is_enabled', true)->pluck('days_before');

        if ($stages->isEmpty()) {
            return 0;
        }

        $today = now()->startOfDay();
        $sent = 0;

        $policies = Policy::whereHas('insuranceType', fn ($q) => $q->whereIn('slug', ['motor-insurance', 'medical-insurance']))
            ->where('policy_expiry_date', '>=', $today)
            ->get();

        foreach ($policies as $policy) {
            $daysUntilExpiry = (int) $today->diffInDays($policy->policy_expiry_date->copy()->startOfDay(), false);

            if (! $stages->contains($daysUntilExpiry)) {
                continue;
            }

            $alreadySent = NotificationLog::where('policy_id', $policy->id)
                ->where('notification_type', 'renewal')
                ->where('reminder_stage', $daysUntilExpiry)
                ->exists();

            if ($alreadySent) {
                continue;
            }

            $body = $this->renderTemplate($setting->body, $policy, $companyName, $daysUntilExpiry, $setting->renewal_payment_link);

            foreach (array_keys($setting->enabledChannels()) as $channel) {
                $status = $dispatcher->dispatch($channel, $policy->contact_number, $setting->subject, $body);

                NotificationLog::create([
                    'policy_id' => $policy->id,
                    'customer_name' => $policy->customer_name,
                    'contact' => $policy->contact_number,
                    'policy_number' => $policy->policy_number,
                    'insurance_type' => $policy->insuranceType?->name,
                    'notification_type' => 'renewal',
                    'reminder_stage' => $daysUntilExpiry,
                    'channel' => $channel,
                    'status' => $status,
                    'message' => $body,
                    'sent_by' => null,
                    'sent_at' => now(),
                ]);

                $sent++;
            }
        }

        return $sent;
    }

    private function renderTemplate(string $template, Policy $policy, string $companyName, ?int $daysRemaining = null, ?string $paymentLink = null): string
    {
        return strtr($template, [
            '{{customer_name}}' => $policy->customer_name,
            '{{company_name}}' => $companyName,
            '{{policy_number}}' => $policy->policy_number,
            '{{insurance_type}}' => $policy->insuranceType?->name ?? '',
            '{{policy_expiry_date}}' => $policy->policy_expiry_date->format('d M Y'),
            '{{days_remaining}}' => (string) $daysRemaining,
            '{{renewal_link}}' => $paymentLink ?? '',
        ]);
    }
}
