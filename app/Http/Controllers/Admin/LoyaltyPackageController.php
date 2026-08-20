<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPackage;
use Illuminate\Http\Request;

class LoyaltyPackageController extends Controller
{
    public function index()
    {
        $packages = LoyaltyPackage::orderBy('id')->get();

        return view('admin.loyalty-packages.index', compact('packages'));
    }

    public function update(Request $request, LoyaltyPackage $loyaltyPackage)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'benefits' => ['nullable', 'string'],
            'discount_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $loyaltyPackage->update($data);

        return redirect()->route('admin.loyalty-packages.index')->with('status', $loyaltyPackage->title.' package updated.');
    }
}
