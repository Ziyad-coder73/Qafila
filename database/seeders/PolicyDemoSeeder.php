<?php

namespace Database\Seeders;

use App\Models\InsuranceType;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PolicyDemoSeeder extends Seeder
{
    public function run(): void
    {
        $agent = User::updateOrCreate(
            ['email' => 'agent@qafilainsurance.com'],
            [
                'name' => 'Qafila Agent',
                'password' => Hash::make('Agent@2026'),
                'role' => 'agent',
                'is_active' => true,
            ]
        );

        $minimalPdf = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 200 200]>>endobj\nxref\n0 4\ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n0\n%%EOF";

        $motor = InsuranceType::where('slug', 'motor-insurance')->first();
        $medical = InsuranceType::where('slug', 'medical-insurance')->first();

        $demoPolicies = [
            [
                'customer_name' => 'Ahmad Al-Sabah',
                'birthday' => '1990-04-12',
                'contact_number' => '96599112233',
                'insurance_type_id' => $motor?->id,
                'insurance_company' => 'Gulf Insurance Group',
                'policy_number' => 'POL-2026-0001',
                'date_of_issue' => now()->subMonth()->startOfMonth(),
                'policy_start_date' => now()->subMonth()->startOfMonth(),
                'policy_expiry_date' => now()->addMonths(11)->startOfMonth(),
                'premium' => 185.500,
                'commission' => 18.550,
                'agent_name' => 'Qafila Agent',
            ],
            [
                'customer_name' => 'Layla Al-Fadhli',
                'birthday' => '1985-09-03',
                'contact_number' => '96599445566',
                'insurance_type_id' => $medical?->id,
                'insurance_company' => 'Kuwait Insurance Company',
                'policy_number' => 'POL-2026-0002',
                'date_of_issue' => now()->startOfMonth(),
                'policy_start_date' => now()->startOfMonth()->addDays(2),
                'policy_expiry_date' => now()->addYear()->startOfMonth(),
                'premium' => 320.000,
                'commission' => 32.000,
                'agent_name' => 'Qafila Agent',
            ],
        ];

        foreach ($demoPolicies as $data) {
            if (Policy::where('policy_number', $data['policy_number'])->exists()) {
                continue;
            }

            $path = 'policies/demo-'.$data['policy_number'].'.pdf';
            Storage::disk('public')->put($path, $minimalPdf);

            $policy = Policy::create($data + [
                'policy_document' => $path,
                'created_by' => $agent->id,
            ]);

            $policy->payments()->create([
                'payment_method' => 'qpay',
                'payment_type' => 'policy_payment',
                'amount' => $data['premium'],
                'paid_at' => $data['policy_start_date'],
                'recorded_by' => $agent->id,
            ]);
        }
    }
}
