<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsuranceType;
use App\Models\Policy;
use App\Models\PolicyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $policies = Policy::with('insuranceType')
            ->filter($request->only(['search', 'insurance_type_id', 'date_from', 'date_to']))
            ->latest('policy_start_date')
            ->paginate(15)
            ->withQueryString();

        $insuranceTypes = InsuranceType::orderBy('name')->get();

        return view('admin.policies.index', compact('policies', 'insuranceTypes'));
    }

    public function create()
    {
        $insuranceTypes = InsuranceType::orderBy('name')->get();
        $policy = new Policy;

        return view('admin.policies.create', compact('insuranceTypes', 'policy'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable', 'date'],
            'contact_number' => ['required', 'string', 'max:30'],
            'insurance_type_id' => ['required', 'exists:insurance_types,id'],
            'insurance_company' => ['required', 'string', 'max:255'],
            'policy_number' => ['required', 'string', 'max:255', 'unique:policies,policy_number'],
            'date_of_issue' => ['required', 'date'],
            'policy_start_date' => ['required', 'date'],
            'policy_expiry_date' => ['required', 'date', 'after_or_equal:policy_start_date'],
            'premium' => ['required', 'numeric', 'min:0'],
            'commission' => ['nullable', 'numeric', 'min:0'],
            'agent_name' => ['required', 'string', 'max:255'],
            'policy_document' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'payment_method' => ['required', 'in:qpay,payment_link'],
            'payment_type' => ['required', 'in:policy_payment,extra_payment'],
            'payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $data['policy_document'] = $request->file('policy_document')->store('policies', 'public');
        $data['created_by'] = $request->user()->id;

        $policy = Policy::create($data);

        $paymentDocument = $request->hasFile('payment_document')
            ? $request->file('payment_document')->store('policy-payments', 'public')
            : null;

        $policy->payments()->create([
            'payment_method' => $data['payment_method'],
            'payment_type' => $data['payment_type'],
            'amount' => $data['payment_amount'] ?? null,
            'document' => $paymentDocument,
            'paid_at' => now(),
            'recorded_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.policies.show', $policy)->with('status', 'Policy uploaded successfully.');
    }

    public function show(Policy $policy)
    {
        $policy->load(['insuranceType', 'payments' => fn ($q) => $q->latest('paid_at'), 'payments.recordedBy', 'loyaltyMember']);

        return view('admin.policies.show', compact('policy'));
    }

    public function edit(Policy $policy)
    {
        $insuranceTypes = InsuranceType::orderBy('name')->get();

        return view('admin.policies.edit', compact('policy', 'insuranceTypes'));
    }

    public function update(Request $request, Policy $policy)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable', 'date'],
            'contact_number' => ['required', 'string', 'max:30'],
            'insurance_type_id' => ['required', 'exists:insurance_types,id'],
            'insurance_company' => ['required', 'string', 'max:255'],
            'policy_number' => ['required', 'string', 'max:255', 'unique:policies,policy_number,'.$policy->id],
            'date_of_issue' => ['required', 'date'],
            'policy_start_date' => ['required', 'date'],
            'policy_expiry_date' => ['required', 'date', 'after_or_equal:policy_start_date'],
            'premium' => ['required', 'numeric', 'min:0'],
            'commission' => ['nullable', 'numeric', 'min:0'],
            'agent_name' => ['required', 'string', 'max:255'],
            'policy_document' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        if ($request->hasFile('policy_document')) {
            Storage::disk('public')->delete($policy->policy_document);
            $data['policy_document'] = $request->file('policy_document')->store('policies', 'public');
        }

        $policy->update($data);

        return redirect()->route('admin.policies.show', $policy)->with('status', 'Policy updated.');
    }

    public function destroy(Policy $policy)
    {
        Storage::disk('public')->delete($policy->policy_document);
        foreach ($policy->payments as $payment) {
            if ($payment->document) {
                Storage::disk('public')->delete($payment->document);
            }
        }

        $policy->delete();

        return redirect()->route('admin.policies.index')->with('status', 'Policy removed.');
    }
}
