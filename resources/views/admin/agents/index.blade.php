@extends('admin.layout')

@section('title', 'Agents — Qafila Admin')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Agent Accounts</h1>
        <a href="{{ route('admin.agents.create') }}" class="bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-800 transition">
            + Add Agent
        </a>
    </div>

    <div class="mt-6 bg-white border border-slate-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Email</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($agents as $agent)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $agent->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $agent->email }}</td>
                        <td class="px-4 py-3">
                            @if($agent->is_active)
                                <span class="inline-block px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">Active</span>
                            @else
                                <span class="inline-block px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-xs font-medium">Deactivated</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.agents.edit', $agent) }}" class="text-blue-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}" class="inline" onsubmit="return confirm('Remove this agent account?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">No agent accounts yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
