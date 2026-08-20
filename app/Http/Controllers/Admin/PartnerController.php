<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = User::query()->where('role', 'partner')->with('brand')->orderBy('name')->get();

        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $partner = new User;

        return view('admin.partners.create', compact('brands', 'partner'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8'],
            'brand_id' => ['required', 'exists:brands,id'],
        ]);

        User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => Str::slug($data['username']).'-'.Str::random(6).'@partner.qafila.local',
            'password' => Hash::make($data['password']),
            'role' => 'partner',
            'brand_id' => $data['brand_id'],
            'is_active' => true,
            'login_token' => Str::random(40),
        ]);

        return redirect()->route('admin.partners.index')->with('status', 'Partner account created.');
    }

    public function edit(User $partner)
    {
        abort_unless($partner->role === 'partner', 404);

        $brands = Brand::orderBy('name')->get();

        return view('admin.partners.edit', compact('partner', 'brands'));
    }

    public function update(Request $request, User $partner)
    {
        abort_unless($partner->role === 'partner', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($partner->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'brand_id' => ['required', 'exists:brands,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $partner->name = $data['name'];
        $partner->username = $data['username'];
        $partner->brand_id = $data['brand_id'];
        $partner->is_active = $request->boolean('is_active');

        if (! empty($data['password'])) {
            $partner->password = Hash::make($data['password']);
        }

        $partner->save();

        return redirect()->route('admin.partners.edit', $partner)->with('status', 'Partner account updated.');
    }

    public function destroy(User $partner)
    {
        abort_unless($partner->role === 'partner', 404);

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('status', 'Partner account removed.');
    }

    public function regenerateLink(User $partner)
    {
        abort_unless($partner->role === 'partner', 404);

        $partner->update(['login_token' => Str::random(40)]);

        return redirect()->route('admin.partners.edit', $partner)->with('status', 'Secure login link regenerated.');
    }
}
