<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyMember;
use App\Models\SiteSetting;

class LoyaltyCardPublicController extends Controller
{
    public function show(string $token)
    {
        $member = LoyaltyMember::query()->where('card_token', $token)->firstOrFail();
        $package = $member->package();
        $settings = SiteSetting::query()->pluck('value', 'key');

        return view('loyalty-card.show', compact('member', 'package', 'settings'));
    }
}
