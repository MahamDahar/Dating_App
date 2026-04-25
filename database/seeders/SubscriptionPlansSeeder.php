<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            // ── FREE ──
            [
                'name'         => 'Free',
                'slug'         => 'free',
                'description'  => 'Basic access to Fobia. Get started for free.',
                'price'        => 0.00,
                'duration_days'=> 0,
                'badge_color'  => 'secondary',
                'badge_icon'   => 'person-outline',
                'is_active'    => true,
                'is_featured'  => false,
                'sort_order'   => 1,
                'features'     => json_encode([
                    'Browse profiles',
                    'Send 5 messages/day',
                    'Basic filters',
                    'Pakistan, India, Bangladesh only',
                ]),
            ],

            // ── SILVER ──
            [
                'name'         => 'Silver',
                'slug'         => 'silver',
                'description'  => 'More matches, more connections.',
                'price'        => 4.00,
                'duration_days'=> 30,
                'badge_color'  => 'secondary',
                'badge_icon'   => 'star-outline',
                'is_active'    => true,
                'is_featured'  => false,
                'sort_order'   => 2,
                'features'     => json_encode([
                    'Everything in Free',
                    'Unlimited messages',
                    'Filter by 10 countries',
                    'See who viewed your profile',
                    'Advanced filters',
                ]),
            ],

            // ── GOLD ──
            [
                'name'         => 'Gold',
                'slug'         => 'gold',
                'description'  => 'Unlock all countries and priority matches.',
                'price'        => 6.00,
                'duration_days'=> 30,
                'badge_color'  => 'warning',
                'badge_icon'   => 'trophy-outline',
                'is_active'    => true,
                'is_featured'  => true,  // Most Popular
                'sort_order'   => 3,
                'features'     => json_encode([
                    'Everything in Silver',
                    'Filter by ALL countries',
                    'USA, UK, Canada, UAE & more',
                    'Priority in search results',
                    'Read receipts',
                    'Gold badge on profile',
                ]),
            ],

            // ── PLATINUM ──
            [
                'name'         => 'Platinum',
                'slug'         => 'platinum',
                'description'  => 'The ultimate Fobia experience.',
                'price'        => 10.00,
                'duration_days'=> 30,
                'badge_color'  => 'primary',
                'badge_icon'   => 'diamond-outline',
                'is_active'    => true,
                'is_featured'  => false,
                'sort_order'   => 4,
                'features'     => json_encode([
                    'Everything in Gold',
                    'Platinum badge on profile',
                    'Top of ALL search results',
                    'Unlimited profile boosts',
                    'See full profile visitors list',
                    'Dedicated support',
                    'Early access to new features',
                ]),
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }

        $this->command->info('✅ Subscription plans seeded: Free, Silver, Gold, Platinum');
    }
}