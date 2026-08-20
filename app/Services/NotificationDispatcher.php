<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Sends a message over WhatsApp, SMS, or Email.
 *
 * Email is genuinely sent through Laravel Mail (using whatever MAIL_MAILER
 * is configured — the "log" driver locally, so nothing external is hit).
 *
 * WhatsApp and SMS have no connected gateway (no Meta WhatsApp Cloud API
 * or Twilio/SMS credentials are configured for this project), so those
 * channels are simulated: the message is written to the application log
 * and recorded as "sent" so the rest of the notification system (schedules,
 * templates, logs) can be built and demonstrated end-to-end. Swap the
 * simulateSend() branch for a real HTTP call to a provider once credentials
 * are available — nothing else in the notification system needs to change.
 */
class NotificationDispatcher
{
    public function dispatch(string $channel, string $to, ?string $subject, string $body): string
    {
        return match ($channel) {
            'email' => $this->sendEmail($to, $subject ?? 'Qafila Insurance', $body),
            'whatsapp', 'sms' => $this->simulateSend($channel, $to, $body),
            default => 'failed',
        };
    }

    private function sendEmail(string $to, string $subject, string $body): string
    {
        try {
            Mail::raw($body, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });

            return 'sent';
        } catch (\Throwable $e) {
            Log::error('[Qafila Notifications] Email send failed', ['to' => $to, 'error' => $e->getMessage()]);

            return 'failed';
        }
    }

    private function simulateSend(string $channel, string $to, string $body): string
    {
        Log::info("[Qafila Notifications] SIMULATED {$channel} send (no gateway configured)", [
            'to' => $to,
            'message' => $body,
        ]);

        return 'sent';
    }
}
