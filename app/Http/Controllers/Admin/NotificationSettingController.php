<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Models\RenewalReminderStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class NotificationSettingController extends Controller
{
    public function index()
    {
        $birthday = NotificationSetting::where('type', 'birthday')->firstOrFail();
        $renewal = NotificationSetting::where('type', 'renewal')->firstOrFail();
        $stages = RenewalReminderStage::orderByDesc('days_before')->get();

        return view('admin.notifications.settings', compact('birthday', 'renewal', 'stages'));
    }

    public function updateBirthday(Request $request)
    {
        $data = $request->validate([
            'is_enabled' => ['sometimes', 'boolean'],
            'channel_whatsapp' => ['sometimes', 'boolean'],
            'channel_sms' => ['sometimes', 'boolean'],
            'channel_email' => ['sometimes', 'boolean'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $data['is_enabled'] = $request->boolean('is_enabled');
        $data['channel_whatsapp'] = $request->boolean('channel_whatsapp');
        $data['channel_sms'] = $request->boolean('channel_sms');
        $data['channel_email'] = $request->boolean('channel_email');

        NotificationSetting::where('type', 'birthday')->update($data);

        return redirect()->route('admin.notifications.index')->with('status', 'Birthday notification settings saved.');
    }

    public function updateRenewal(Request $request)
    {
        $data = $request->validate([
            'is_enabled' => ['sometimes', 'boolean'],
            'channel_whatsapp' => ['sometimes', 'boolean'],
            'channel_sms' => ['sometimes', 'boolean'],
            'channel_email' => ['sometimes', 'boolean'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'renewal_payment_link' => ['nullable', 'string', 'max:255'],
            'stages' => ['array'],
        ]);

        $data['is_enabled'] = $request->boolean('is_enabled');
        $data['channel_whatsapp'] = $request->boolean('channel_whatsapp');
        $data['channel_sms'] = $request->boolean('channel_sms');
        $data['channel_email'] = $request->boolean('channel_email');

        NotificationSetting::where('type', 'renewal')->update([
            'is_enabled' => $data['is_enabled'],
            'channel_whatsapp' => $data['channel_whatsapp'],
            'channel_sms' => $data['channel_sms'],
            'channel_email' => $data['channel_email'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'renewal_payment_link' => $data['renewal_payment_link'],
        ]);

        $enabledStages = array_keys($data['stages'] ?? []);
        RenewalReminderStage::query()->update(['is_enabled' => false]);
        RenewalReminderStage::whereIn('days_before', $enabledStages)->update(['is_enabled' => true]);

        return redirect()->route('admin.notifications.index')->with('status', 'Renewal reminder settings saved.');
    }

    public function runNow()
    {
        Artisan::call('qafila:send-notifications');
        $output = trim(str_replace("\n", ' ', Artisan::output()));

        return redirect()->route('admin.notifications.log.index')->with('status', 'Notification sweep completed. '.$output);
    }
}
