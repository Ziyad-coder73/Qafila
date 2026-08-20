<?php

namespace App\Http\Controllers;

use App\Models\InsuranceType;
use App\Models\PaymentMethod;
use App\Models\SiteSetting;
use App\Models\SocialLink;

class HomeController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::query()->pluck('value', 'key');
        $insuranceTypes = InsuranceType::active()->get();
        $socialLinks = SocialLink::active()->get();
        $paymentMethods = PaymentMethod::active()->get();

        return view('home', compact('settings', 'insuranceTypes', 'socialLinks', 'paymentMethods'));
    }
}
