<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ── All Permissions ──
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view',              'display_name' => 'View Dashboard',              'group' => 'Dashboard'],

            // User Management
            ['name' => 'users.view',                  'display_name' => 'View Users',                  'group' => 'User Management'],
            ['name' => 'users.edit',                  'display_name' => 'Edit Users',                  'group' => 'User Management'],
            ['name' => 'users.delete',                'display_name' => 'Delete Users',                'group' => 'User Management'],
            ['name' => 'users.login-as',              'display_name' => 'Login As User',               'group' => 'User Management'],
            ['name' => 'verification.view',           'display_name' => 'View Verifications',          'group' => 'User Management'],
            ['name' => 'matches.view',                'display_name' => 'View Matches',                'group' => 'User Management'],
            ['name' => 'messages.view',               'display_name' => 'View Messages',               'group' => 'User Management'],

            // Moderation
            ['name' => 'reports.view',                'display_name' => 'View Reports',                'group' => 'Moderation'],
            ['name' => 'reports.action',              'display_name' => 'Take Action on Reports',      'group' => 'Moderation'],
            ['name' => 'content.view',                'display_name' => 'View Content Moderation',     'group' => 'Moderation'],
            ['name' => 'content.action',              'display_name' => 'Take Action on Content',      'group' => 'Moderation'],
            ['name' => 'support.view',                'display_name' => 'View Support Tickets',        'group' => 'Moderation'],
            ['name' => 'support.reply',               'display_name' => 'Reply to Support Tickets',    'group' => 'Moderation'],

            // Finance
            ['name' => 'subscriptions.view',          'display_name' => 'View Subscriptions',          'group' => 'Finance'],
            ['name' => 'subscriptions.manage',        'display_name' => 'Manage Subscriptions',        'group' => 'Finance'],
            ['name' => 'payments.view',               'display_name' => 'View Payments',               'group' => 'Finance'],
            ['name' => 'payments.refund',             'display_name' => 'Issue Refunds',               'group' => 'Finance'],

            // Engagement
            ['name' => 'notifications.view',          'display_name' => 'View Notifications',          'group' => 'Engagement'],
            ['name' => 'notifications.send',          'display_name' => 'Send Notifications',          'group' => 'Engagement'],
            ['name' => 'marketing.view',              'display_name' => 'View Marketing',              'group' => 'Engagement'],
            ['name' => 'marketing.manage',            'display_name' => 'Manage Marketing',            'group' => 'Engagement'],
            ['name' => 'analytics.view',              'display_name' => 'View Analytics',              'group' => 'Engagement'],

            // System
            ['name' => 'admin_management.view',       'display_name' => 'View Admin Management',       'group' => 'System'],
            ['name' => 'admin_management.manage',     'display_name' => 'Manage Admins & Roles',       'group' => 'System'],
            ['name' => 'settings.view',               'display_name' => 'View Settings',               'group' => 'System'],
            ['name' => 'settings.manage',             'display_name' => 'Manage Settings',             'group' => 'System'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // ── Roles ──
        $roles = [
            [
                'name'         => 'super_admin',
                'display_name' => 'Super Admin',
                'color'        => 'danger',
                'description'  => 'Full access to everything.',
            ],
            [
                'name'         => 'moderator',
                'display_name' => 'Moderator',
                'color'        => 'warning',
                'description'  => 'Access to reports and content moderation.',
            ],
            [
                'name'         => 'finance',
                'display_name' => 'Finance',
                'color'        => 'success',
                'description'  => 'Access to subscriptions and payments.',
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(['name' => $roleData['name']], $roleData);

            // ── Assign default permissions per role ──
            if ($role->name === 'super_admin') {
                // Super admin gets ALL permissions
                $role->permissions()->sync(Permission::pluck('id'));
            }

            if ($role->name === 'moderator') {
                $role->permissions()->sync(
                    Permission::whereIn('name', [
                        'dashboard.view',
                        'reports.view', 'reports.action',
                        'content.view', 'content.action',
                        'support.view', 'support.reply',
                        'notifications.view',
                    ])->pluck('id')
                );
            }

            if ($role->name === 'finance') {
                $role->permissions()->sync(
                    Permission::whereIn('name', [
                        'dashboard.view',
                        'subscriptions.view', 'subscriptions.manage',
                        'payments.view', 'payments.refund',
                        'analytics.view',
                    ])->pluck('id')
                );
            }
        }
    }
}