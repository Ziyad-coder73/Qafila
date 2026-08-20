@if ($errors->any())
    <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-4">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <label for="name" class="block text-sm font-medium text-slate-700">Brand Name</label>
    <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}" required
        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
</div>

<div>
    <label for="logo" class="block text-sm font-medium text-slate-700">Brand Logo</label>
    @if($brand->logo)
        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="mt-2 h-16 w-16 rounded-lg object-cover border border-slate-200">
    @endif
    <input type="file" name="logo" id="logo" accept="image/*"
        class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:text-sm file:font-medium hover:file:bg-blue-100">
</div>

<div>
    <label for="location" class="block text-sm font-medium text-slate-700">Business Location</label>
    <input type="text" name="location" id="location" value="{{ old('location', $brand->location) }}"
        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
</div>

<div>
    <label for="contact_info" class="block text-sm font-medium text-slate-700">Contact Information</label>
    <input type="text" name="contact_info" id="contact_info" value="{{ old('contact_info', $brand->contact_info) }}"
        placeholder="Phone, email, etc."
        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
</div>

<div>
    <label for="owner_name" class="block text-sm font-medium text-slate-700">Owner Name</label>
    <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name', $brand->owner_name) }}"
        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
    <p class="mt-1 text-xs text-slate-400">Admin use only — never shown to customers.</p>
</div>

<label class="flex items-center gap-2 text-sm text-slate-700">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" @checked(old('is_active', $brand->is_active))>
    Visible to customers
</label>
