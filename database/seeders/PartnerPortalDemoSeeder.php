<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\LoyaltyMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PartnerPortalDemoSeeder extends Seeder
{
    public function run(): void
    {
        $brand = Brand::updateOrCreate(
            ['name' => 'Desert Rose Cafe'],
            [
                'location' => 'Salmiya, Block 4',
                'contact_info' => '+965 5555 1234',
                'owner_name' => 'Fatima Al-Otaibi',
                'is_active' => true,
            ]
        );

        $brand->voucherPackages()->updateOrCreate(
            ['title' => 'Gold Package - 20% Off'],
            [
                'description' => '20% off all beverages for Gold members',
                'is_available' => true,
            ]
        );

        User::updateOrCreate(
            ['username' => 'desertrose_partner'],
            [
                'name' => 'Desert Rose Cafe Staff',
                'email' => 'desertrose-partner@partner.qafila.local',
                'password' => Hash::make('Partner@2026'),
                'role' => 'partner',
                'brand_id' => $brand->id,
                'is_active' => true,
                'login_token' => Str::random(40),
            ]
        );

        LoyaltyMember::updateOrCreate(
            ['membership_number' => 'QAF-000123'],
            [
                'full_name' => 'Ahmad Al-Sabah',
                'phone' => '96599112233',
                'loyalty_package' => 'gold',
                'status' => 'active',
                'card_issued_at' => now()->subMonths(2),
                'expires_at' => now()->addYear(),
            ]
        );
    }
}
