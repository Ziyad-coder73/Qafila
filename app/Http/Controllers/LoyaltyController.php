<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\SiteSetting;

class LoyaltyController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::query()->pluck('value', 'key');
        $brands = Brand::active()
            ->with(['voucherPackages' => fn ($query) => $query->orderBy('sort_order')])
            ->get();

        return view('loyalty.index', compact('settings', 'brands'));
    }
}
