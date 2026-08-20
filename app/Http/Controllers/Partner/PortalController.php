<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyMember;
use App\Models\VoucherPackage;
use App\Models\VoucherRedemption;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index()
    {
        return view('partner.portal.index');
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'membership_number' => ['required', 'string'],
        ]);

        $member = LoyaltyMember::query()
            ->where('membership_number', $data['membership_number'])
            ->first();

        if (! $member) {
            return back()->withErrors(['membership_number' => 'No membership found with that number.']);
        }

        return redirect()->route('partner.members.show', $member);
    }

    public function show(Request $request, LoyaltyMember $loyaltyMember)
    {
        $user = $request->user();

        $voucherPackagesQuery = VoucherPackage::query()->with('brand')->where('is_available', true);

        if ($user->isPartner()) {
            $voucherPackagesQuery->where('brand_id', $user->brand_id);
        }

        $voucherPackages = $voucherPackagesQuery->orderBy('sort_order')->get();

        $redemptions = $loyaltyMember->redemptions()
            ->with(['brand', 'voucherPackage'])
            ->when($user->isPartner(), fn ($query) => $query->where('brand_id', $user->brand_id))
            ->latest('redeemed_at')
            ->get();

        return view('partner.portal.show', [
            'member' => $loyaltyMember,
            'voucherPackages' => $voucherPackages,
            'redemptions' => $redemptions,
        ]);
    }

    public function redeem(Request $request, LoyaltyMember $loyaltyMember)
    {
        $data = $request->validate([
            'voucher_package_id' => ['required', 'exists:voucher_packages,id'],
        ]);

        $voucherPackage = VoucherPackage::findOrFail($data['voucher_package_id']);
        $user = $request->user();

        if ($user->isPartner() && $voucherPackage->brand_id !== $user->brand_id) {
            abort(403, 'You can only redeem vouchers for your own brand.');
        }

        if (! $voucherPackage->is_available) {
            return back()->withErrors(['voucher_package_id' => 'This voucher package is currently unavailable.']);
        }

        if (! $loyaltyMember->isValid()) {
            return back()->withErrors(['voucher_package_id' => 'This membership is not active or has expired.']);
        }

        VoucherRedemption::create([
            'loyalty_member_id' => $loyaltyMember->id,
            'brand_id' => $voucherPackage->brand_id,
            'voucher_package_id' => $voucherPackage->id,
            'partner_id' => $user->id,
            'redeemed_at' => now(),
        ]);

        return redirect()->route('partner.members.show', $loyaltyMember)->with('status', 'Voucher redeemed successfully.');
    }
}
