<?php

namespace App\Http\Controllers;

use App\Models\InsuranceRequest;
use App\Models\InsuranceType;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class InsuranceRequestController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'civil_id' => ['required', 'string', 'max:30'],
            'insurance_type_id' => ['required', 'exists:insurance_types,id'],
        ]);

        $insuranceRequest = InsuranceRequest::create($data);
        $insuranceType = InsuranceType::findOrFail($data['insurance_type_id']);

        $message = "New Insurance Request\n"
            ."Name: {$insuranceRequest->full_name}\n"
            ."Phone: {$insuranceRequest->phone}\n"
            ."Civil ID: {$insuranceRequest->civil_id}\n"
            ."Insurance Type: {$insuranceType->name}";

        $whatsappNumber = SiteSetting::get('whatsapp_number');
        $whatsappUrl = 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode($message);

        return view('insurance-requests.sent', [
            'whatsappUrl' => $whatsappUrl,
            'insuranceRequest' => $insuranceRequest,
        ]);
    }
}
