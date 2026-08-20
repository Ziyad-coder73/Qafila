<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsuranceType;
use App\Models\LoyaltyMember;
use App\Models\LoyaltyPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManualLoyaltyCardController extends Controller
{
    public function index(Request $request)
    {
        $members = LoyaltyMember::whereNull('policy_id')
            ->with(['insuranceType', 'issuedBy'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $like = '%'.$request->string('search').'%';
                $query->where(function ($q) use ($like) {
                    $q->where('full_name', 'like', $like)
                        ->orWhere('membership_number', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('id_number', 'like', $like);
                });
            })
            ->latest('card_issued_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.loyalty-cards.manual-index', compact('members'));
    }

    public function create()
    {
        $insuranceTypes = InsuranceType::orderBy('name')->get();
        $packages = LoyaltyPackage::orderBy('id')->get();

        return view('admin.loyalty-cards.manual-create', compact('insuranceTypes', 'packages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'id_number' => ['required', 'string', 'max:50'],
            'insurance_company' => ['required', 'string', 'max:255'],
            'insurance_type_id' => ['nullable', 'exists:insurance_types,id'],
            'loyalty_package' => ['required', 'in:silver,gold,platinum'],
        ]);

        $member = LoyaltyMember::create($data + [
            'membership_number' => LoyaltyMember::generateMembershipNumber(),
            'card_token' => Str::random(40),
            'status' => 'active',
            'card_issued_at' => now(),
            'expires_at' => now()->addYear(),
            'issued_by' => $request->user()->id,
            'delivery_status' => 'pending',
        ]);

        return redirect()->route('admin.loyalty-cards.show', $member)->with('status', 'Loyalty card generated successfully.');
    }
}
