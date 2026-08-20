<?php

namespace Database\Seeders;

use App\Models\InsuranceType;
use App\Models\PaymentMethod;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'company_name' => 'Qafila Insurance',
            'company_tagline' => 'Your trusted partner for comprehensive insurance solutions',
            'company_about' => 'Qafila Insurance is dedicated to providing reliable, affordable, and comprehensive insurance coverage tailored to your needs. With years of experience in the insurance industry, we work with leading insurance companies to bring you the best coverage options for your motor, medical, life, travel, and property insurance needs.',
            'company_phone' => '+965 0000 0000',
            'company_email' => 'info@qafilainsurance.com',
            'company_address' => 'Kuwait City, Kuwait',
            'whatsapp_number' => '96500000000',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $insuranceTypes = [
            ['name' => 'Motor Insurance', 'description' => 'Comprehensive and third-party coverage for your vehicle.'],
            ['name' => 'Medical Insurance', 'description' => 'Health coverage for individuals and families.'],
            ['name' => 'Life Insurance', 'description' => 'Protect your family\'s financial future.'],
            ['name' => 'Travel Insurance', 'description' => 'Stay covered while traveling abroad.'],
            ['name' => 'Property Insurance', 'description' => 'Protection for your home and belongings.'],
        ];

        foreach ($insuranceTypes as $index => $type) {
            InsuranceType::updateOrCreate(
                ['slug' => Str::slug($type['name'])],
                [
                    'name' => $type['name'],
                    'description' => $type['description'],
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }

        $socialLinks = [
            ['platform' => 'Instagram', 'url' => 'https://instagram.com/qafilainsurance'],
            ['platform' => 'Facebook', 'url' => 'https://facebook.com/qafilainsurance'],
            ['platform' => 'X (Twitter)', 'url' => 'https://x.com/qafilainsurance'],
            ['platform' => 'WhatsApp', 'url' => 'https://wa.me/96500000000'],
        ];

        foreach ($socialLinks as $index => $link) {
            SocialLink::updateOrCreate(
                ['platform' => $link['platform']],
                [
                    'url' => $link['url'],
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }

        $paymentMethods = [
            ['name' => 'QPay', 'description' => 'Pay securely via QPay.'],
            ['name' => 'Payment Link', 'description' => 'Pay via a secure online payment link.'],
            ['name' => 'Cash', 'description' => 'Pay in person at our office.'],
            ['name' => 'Bank Transfer', 'description' => 'Direct bank transfer to our account.'],
        ];

        foreach ($paymentMethods as $index => $method) {
            PaymentMethod::updateOrCreate(
                ['name' => $method['name']],
                [
                    'description' => $method['description'],
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
