<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; }
        h1 { font-size: 16px; color: #1d4ed8; margin-bottom: 0; }
        p.subtitle { color: #64748b; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #cbd5e1; padding: 5px 6px; text-align: left; }
        th { background: #f1f5f9; }
    </style>
</head>
<body>
    <h1>Qafila Insurance — Policy Report</h1>
    <p class="subtitle">Month: {{ $month }} &middot; {{ $policies->count() }} policies</p>

    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Insurance Type</th>
                <th>Company</th>
                <th>Policy #</th>
                <th>Start</th>
                <th>Expiry</th>
                <th>Premium</th>
                <th>Commission</th>
                <th>Agent</th>
            </tr>
        </thead>
        <tbody>
            @forelse($policies as $policy)
                <tr>
                    <td>{{ $policy->customer_name }}</td>
                    <td>{{ $policy->insuranceType->name ?? '' }}</td>
                    <td>{{ $policy->insurance_company }}</td>
                    <td>{{ $policy->policy_number }}</td>
                    <td>{{ $policy->policy_start_date->format('d M Y') }}</td>
                    <td>{{ $policy->policy_expiry_date->format('d M Y') }}</td>
                    <td>{{ number_format($policy->premium, 3) }}</td>
                    <td>{{ number_format($policy->commission ?? 0, 3) }}</td>
                    <td>{{ $policy->agent_name }}</td>
                </tr>
            @empty
                <tr><td colspan="9">No policies found for this month.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
