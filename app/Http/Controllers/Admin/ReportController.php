<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PolicyReportMail;
use App\Models\InsuranceType;
use App\Models\Policy;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $insuranceTypes = InsuranceType::orderBy('name')->get();

        $policies = $this->monthlyPolicies($month, $request)->get();

        $totals = [
            'count' => $policies->count(),
            'premium' => $policies->sum('premium'),
            'commission' => $policies->sum('commission'),
        ];

        return view('admin.reports.index', compact('month', 'insuranceTypes', 'policies', 'totals'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $month = $request->input('month', now()->format('Y-m'));
        $policies = $this->monthlyPolicies($month, $request)->get();

        $filename = "qafila-policy-report-{$month}.csv";

        return response()->streamDownload(function () use ($policies) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Customer Name', 'Birthday', 'Contact Number', 'Insurance Type', 'Insurance Company',
                'Policy Number', 'Date of Issue', 'Policy Start', 'Policy Expiry', 'Premium', 'Commission', 'Agent Name',
            ]);

            foreach ($policies as $policy) {
                fputcsv($handle, [
                    $policy->customer_name,
                    optional($policy->birthday)->format('Y-m-d'),
                    $policy->contact_number,
                    $policy->insuranceType->name ?? '',
                    $policy->insurance_company,
                    $policy->policy_number,
                    $policy->date_of_issue->format('Y-m-d'),
                    $policy->policy_start_date->format('Y-m-d'),
                    $policy->policy_expiry_date->format('Y-m-d'),
                    $policy->premium,
                    $policy->commission,
                    $policy->agent_name,
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportPdf(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $policies = $this->monthlyPolicies($month, $request)->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('month', 'policies'));

        return $pdf->download("qafila-policy-report-{$month}.pdf");
    }

    public function email(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'month' => ['required'],
        ]);

        $month = $data['month'];
        $policies = $this->monthlyPolicies($month, $request)->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('month', 'policies'));
        $filename = "qafila-policy-report-{$month}.pdf";

        Mail::to($data['email'])->send(new PolicyReportMail($month, $pdf->output(), $filename));

        return back()->with('status', 'Report emailed to '.$data['email'].'.');
    }

    private function monthlyPolicies(string $month, Request $request)
    {
        [$year, $monthNumber] = array_pad(explode('-', $month), 2, null);

        return Policy::with('insuranceType')
            ->filter($request->only(['search', 'insurance_type_id']))
            ->whereYear('policy_start_date', $year)
            ->whereMonth('policy_start_date', $monthNumber)
            ->orderBy('policy_start_date');
    }
}
