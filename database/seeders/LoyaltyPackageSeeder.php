<?php

namespace Database\Seeders;

use App\Models\LoyaltyPackage;
use Illuminate\Database\Seeder;

class LoyaltyPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'slug' => 'silver',
                'title' => 'Silver',
                'discount_percentage' => 5,
                'benefits' => "5% discount at all Qafila partner brands\nPriority customer support\nBirthday greeting",
            ],
            [
                'slug' => 'gold',
                'title' => 'Gold',
                'discount_percentage' => 10,
                'benefits' => "10% discount at all Qafila partner brands\nPriority customer support\nBirthday greeting with special offer\nEarly access to new partner promotions",
            ],
            [
                'slug' => 'platinum',
                'title' => 'Platinum',
                'discount_percentage' => 20,
                'benefits' => "20% discount at all Qafila partner brands\nDedicated priority support line\nBirthday greeting with premium gift offer\nEarly access to new partner promotions\nExclusive platinum-only partner deals",
            ],
        ];

        foreach ($packages as $package) {
            LoyaltyPackage::updateOrCreate(['slug' => $package['slug']], $package);
        }
    }
}
