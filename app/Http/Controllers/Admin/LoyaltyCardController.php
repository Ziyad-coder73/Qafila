<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyMember;
use App\Models\LoyaltyPackage;
use App\Models\Policy;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoyaltyCardController extends Controller
{
    public function create(Policy $policy)
    {
        abort_if($policy->loyaltyMember, 409, 'A loyalty card has already been issued for this policy.');

        $packages = LoyaltyPackage::orderBy('id')->get();

        return view('admin.loyalty-cards.create', compact('policy', 'packages'));
    }

    public function store(Request $request, Policy $policy)
    {
        abort_if($policy->loyaltyMember, 409, 'A loyalty card has already been issued for this policy.');

        $data = $request->validate([
            'loyalty_package' => ['required', 'in:silver,gold,platinum'],
        ]);

        if ($policy->isExpired()) {
            return back()->withErrors(['loyalty_package' => 'This policy has expired and is not eligible for a loyalty card.']);
        }

        $member = LoyaltyMember::create([
            'policy_id' => $policy->id,
            'membership_number' => LoyaltyMember::generateMembershipNumber(),
            'card_token' => Str::random(40),
            'full_name' => $policy->customer_name,
            'phone' => $policy->contact_number,
            'loyalty_package' => $data['loyalty_package'],
            'status' => 'active',
            'card_issued_at' => now(),
            'expires_at' => $policy->policy_expiry_date,
            'issued_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.loyalty-cards.show', $member)->with('status', 'Loyalty card generated successfully.');
    }

    public function show(LoyaltyMember $loyaltyMember)
    {
        $loyaltyMember->load('policy.insuranceType');
        $package = $loyaltyMember->package();
        $settings = SiteSetting::query()->pluck('value', 'key');

        $portalUrl = route('loyalty-card.public', $loyaltyMember->card_token);

        $message = "Welcome to Qafila Loyalty, {$loyaltyMember->full_name}! 🎉\n\n"
            ."Your digital ".ucfirst($loyaltyMember->loyalty_package)." Loyalty Card is ready.\n"
            ."Membership No: {$loyaltyMember->membership_number}\n\n"
            ."View your card and benefits here:\n{$portalUrl}\n\n"
            ."Show this card at any Qafila partner brand to redeem your benefits. Thank you for choosing "
            .($settings['company_name'] ?? 'Qafila Insurance').'!';

        $whatsappNumber = preg_replace('/\D+/', '', $loyaltyMember->phone);
        $whatsappUrl = 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode($message);

        return view('admin.loyalty-cards.show', compact('loyaltyMember', 'package', 'portalUrl', 'whatsappUrl'));
    }

    public function updateDelivery(Request $request, LoyaltyMember $loyaltyMember)
    {
        $data = $request->validate([
            'delivery_method' => ['required', 'in:whatsapp,sms,email'],
            'delivery_status' => ['required', 'in:sent,pending,failed'],
        ]);

        $loyaltyMember->update($data);

        return back()->with('status', 'Delivery status updated.');
    }
}
