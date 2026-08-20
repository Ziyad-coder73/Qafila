<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    public function index()
    {
        $agents = User::query()->where('role', 'agent')->orderBy('name')->get();

        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        $agent = new User;

        return view('admin.agents.create', compact('agent'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'agent',
            'is_active' => true,
        ]);

        return redirect()->route('admin.agents.index')->with('status', 'Agent account created.');
    }

    public function edit(User $agent)
    {
        abort_unless($agent->role === 'agent', 404);

        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, User $agent)
    {
        abort_unless($agent->role === 'agent', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($agent->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $agent->name = $data['name'];
        $agent->email = $data['email'];
        $agent->is_active = $request->boolean('is_active');

        if (! empty($data['password'])) {
            $agent->password = Hash::make($data['password']);
        }

        $agent->save();

        return redirect()->route('admin.agents.edit', $agent)->with('status', 'Agent account updated.');
    }

    public function destroy(User $agent)
    {
        abort_unless($agent->role === 'agent', 404);

        $agent->delete();

        return redirect()->route('admin.agents.index')->with('status', 'Agent account removed.');
    }
}
