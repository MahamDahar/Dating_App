<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LegalPage;     

class LegalPageSeeder extends Seeder {
    public function run(): void {
        $pages = [
            ['type' => 'privacy_policy',        'title' => 'Privacy Policy'],
            ['type' => 'terms_conditions',       'title' => 'Terms & Conditions'],
            ['type' => 'community_guidelines',   'title' => 'Community Guidelines'],
            ['type' => 'cookie_policy',          'title' => 'Cookie Policy'],
        ];

        foreach ($pages as $page) {
            LegalPage::firstOrCreate(
                ['type' => $page['type']],
                [
                    'title'           => $page['title'],
                    'content'         => 'Content coming soon...',
                    'last_updated_at' => now(),
                ]
            );
        }
    }
}
