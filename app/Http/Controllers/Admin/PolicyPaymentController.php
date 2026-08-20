<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\PolicyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PolicyPaymentController extends Controller
{
    public function store(Request $request, Policy $policy)
    {
        $data = $request->validate([
            'payment_method' => ['required', 'in:qpay,payment_link'],
            'payment_type' => ['required', 'in:policy_payment,extra_payment'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'paid_at' => ['required', 'date'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('policy-payments', 'public');
        }

        $data['recorded_by'] = $request->user()->id;

        $policy->payments()->create($data);

        return redirect()->route('admin.policies.show', $policy)->with('status', 'Payment recorded.');
    }

    public function destroy(Policy $policy, PolicyPayment $payment)
    {
        if ($payment->document) {
            Storage::disk('public')->delete($payment->document);
        }

        $payment->delete();

        return redirect()->route('admin.policies.show', $policy)->with('status', 'Payment removed.');
    }
}
