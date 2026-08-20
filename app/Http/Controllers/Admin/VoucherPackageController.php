<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\VoucherPackage;
use Illuminate\Http\Request;

class VoucherPackageController extends Controller
{
    public function store(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_available' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['is_available'] = $request->boolean('is_available');

        $brand->voucherPackages()->create($data);

        return redirect()->route('admin.brands.edit', $brand)->with('status', 'Voucher package added.');
    }

    public function update(Request $request, Brand $brand, VoucherPackage $voucher)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_available' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['is_available'] = $request->boolean('is_available');

        $voucher->update($data);

        return redirect()->route('admin.brands.edit', $brand)->with('status', 'Voucher package updated.');
    }

    public function destroy(Brand $brand, VoucherPackage $voucher)
    {
        $voucher->delete();

        return redirect()->route('admin.brands.edit', $brand)->with('status', 'Voucher package removed.');
    }
}
