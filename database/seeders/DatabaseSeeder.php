<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\Task;

use App\Models\CurrencyRate;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@taskgo.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create Sample Agents
        $agent1 = Agent::create([
            'name' => 'Demo Agent',
            'email' => 'agent@taskgo.com',
            'password' => Hash::make('password'),
            'phone' => '01700000001',
            'commission_rate' => 10.00,
            'status' => 'active',
            'agent_code' => 'AGT' . rand(10000, 99999),
        ]);

        $agent2 = Agent::create([
            'name' => 'Test Agent',
            'email' => 'agent2@taskgo.com',
            'password' => Hash::make('password'),
            'phone' => '01700000002',
            'commission_rate' => 12.00,
            'status' => 'active',
            'agent_code' => 'AGT' . rand(10000, 99999),
        ]);

        // Create Sample Users
        $user1 = User::create([
            'name' => 'Demo User',
            'email' => 'user@taskgo.com',
            'password' => Hash::make('password'),
            'phone' => '01800000001',
            'referral_code' => 'DEMO001',
            'agent_id' => $agent1->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Update wallet for user1 (already created by boot method)
        $user1->wallet->update([
            'main_balance' => 100.00,
            'pending_balance' => 0,
            'withdraw_balance' => 100.00,
            'total_earned' => 110.00,
            'total_withdrawn' => 0,
        ]);

        $user2 = User::create([
            'name' => 'Test User',
            'email' => 'user2@taskgo.com',
            'password' => Hash::make('password'),
            'phone' => '01800000002',
            'referral_code' => 'TEST002',
            'referred_by' => $user1->id,
            'agent_id' => $agent1->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Update wallet for user2
        $user2->wallet->update([
            'main_balance' => 50.00,
            'pending_balance' => 20.00,
            'withdraw_balance' => 50.00,
            'total_earned' => 70.00,
            'total_withdrawn' => 0,
        ]);

        // Create Payment Methods
        $paymentMethods = [
            [
                'name' => 'bKash',
                'code' => 'bkash',
                'type' => 'both',
                'instructions' => 'Send money to bKash: 01700000000 (Task Go) and submit the transaction ID',
                'min_amount' => 100,
                'max_amount' => 25000,
                'is_active' => true,
                'account_details' => json_encode(['number' => '01700000000', 'type' => 'personal']),
            ],
            [
                'name' => 'Nagad',
                'code' => 'nagad',
                'type' => 'both',
                'instructions' => 'Send money to Nagad: 01800000000 (Task Go) and submit the transaction ID',
                'min_amount' => 100,
                'max_amount' => 25000,
                'is_active' => true,
                'account_details' => json_encode(['number' => '01800000000', 'type' => 'personal']),
            ],
            [
                'name' => 'Rocket',
                'code' => 'rocket',
                'type' => 'both',
                'instructions' => 'Send money to Rocket: 01900000000 (Task Go) and submit the transaction ID',
                'min_amount' => 100,
                'max_amount' => 25000,
                'is_active' => true,
                'account_details' => json_encode(['number' => '01900000000', 'type' => 'personal']),
            ],
            [
                'name' => 'Bank Transfer',
                'code' => 'bank_transfer',
                'type' => 'both',
                'instructions' => 'Transfer to Dutch Bangla Bank, A/C: 1234567890, Name: Task Go Ltd',
                'min_amount' => 500,
                'max_amount' => 100000,
                'is_active' => true,
                'account_details' => json_encode([
                    'bank_name' => 'Dutch Bangla Bank',
                    'account_number' => '1234567890',
                    'account_name' => 'Task Go Ltd',
                    'branch' => 'Dhaka Main',
                    'routing' => '123456789'
                ]),
            ],
            [
                'name' => 'Binance USDT',
                'code' => 'binance_usdt',
                'type' => 'both',
                'instructions' => 'Send USDT (TRC20) to the provided address',
                'min_amount' => 10,
                'max_amount' => 10000,
                'currency' => 'USDT',
                'is_active' => true,
                'account_details' => json_encode([
                    'address' => 'TRx1234567890abcdefghijklmnop',
                    'network' => 'TRC20'
                ]),
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }

        // Create Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'Task Go', 'type' => 'text'],
            ['key' => 'support_email', 'value' => 'support@taskgo.com', 'type' => 'text'],
            ['key' => 'support_phone', 'value' => '+880 1700-000000', 'type' => 'text'],
            ['key' => 'currency_symbol', 'value' => '৳', 'type' => 'text'],
            ['key' => 'daily_task_limit', 'value' => '10', 'type' => 'number'],
            ['key' => 'default_task_reward', 'value' => '10', 'type' => 'number'],
            ['key' => 'min_deposit', 'value' => '100', 'type' => 'number'],
            ['key' => 'max_deposit', 'value' => '50000', 'type' => 'number'],
            ['key' => 'min_withdrawal', 'value' => '500', 'type' => 'number'],
            ['key' => 'max_withdrawal', 'value' => '25000', 'type' => 'number'],
            ['key' => 'withdrawal_fee_percent', 'value' => '0', 'type' => 'number'],
            ['key' => 'referral_signup_bonus', 'value' => '10', 'type' => 'number'],
            ['key' => 'referral_task_commission', 'value' => '5', 'type' => 'number'],
            ['key' => 'default_agent_commission', 'value' => '10', 'type' => 'number'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        // Create Sample Tasks
        $tasks = [
            [
                'title' => 'Like Our Facebook Page',
                'description' => 'Like and follow our official Facebook page to earn rewards.',
                'instructions' => "Go to our Facebook page\nClick the Like button\nClick the Follow button\nTake a screenshot showing you liked the page",
                'category' => 'social',
                'reward' => 5.00,
                'total_limit' => 1000,
                'daily_limit' => 100,
                'proof_type' => 'screenshot',
                'task_url' => 'https://facebook.com/taskgo',
                'estimated_time' => 2,
                'status' => 'active',
                'difficulty' => 'easy',
                'priority' => 10,
            ],
            [
                'title' => 'Subscribe to YouTube Channel',
                'description' => 'Subscribe to our YouTube channel and watch the latest video.',
                'instructions' => "Go to our YouTube channel\nClick Subscribe button\nClick the Bell icon for notifications\nWatch at least 1 minute of our latest video\nTake a screenshot showing subscription",
                'category' => 'video',
                'reward' => 10.00,
                'total_limit' => 500,
                'daily_limit' => 50,
                'proof_type' => 'screenshot',
                'task_url' => 'https://youtube.com/@taskgo',
                'estimated_time' => 5,
                'status' => 'active',
                'difficulty' => 'easy',
                'priority' => 9,
            ],
            [
                'title' => 'Follow on Instagram',
                'description' => 'Follow our Instagram account and like our recent posts.',
                'instructions' => "Go to our Instagram profile\nClick Follow button\nLike our 3 most recent posts\nTake a screenshot showing you followed",
                'category' => 'social',
                'reward' => 5.00,
                'total_limit' => 800,
                'daily_limit' => 80,
                'proof_type' => 'screenshot',
                'task_url' => 'https://instagram.com/taskgo',
                'estimated_time' => 3,
                'status' => 'active',
                'difficulty' => 'easy',
                'priority' => 8,
            ],
            [
                'title' => 'Complete Survey',
                'description' => 'Complete a short survey about your earning app preferences.',
                'instructions' => "Click the survey link\nAnswer all questions honestly\nSubmit the survey\nCopy the completion code\nSubmit the code as proof",
                'category' => 'survey',
                'reward' => 15.00,
                'total_limit' => 200,
                'daily_limit' => 20,
                'proof_type' => 'text',
                'task_url' => 'https://forms.google.com/taskgo-survey',
                'estimated_time' => 10,
                'status' => 'active',
                'difficulty' => 'medium',
                'priority' => 7,
            ],
            [
                'title' => 'Download & Review App',
                'description' => 'Download our partner app and leave a 5-star review.',
                'instructions' => "Download the app from Play Store\nOpen and use the app for 5 minutes\nGo back to Play Store\nLeave a 5-star review with your feedback\nTake a screenshot of your review",
                'category' => 'app',
                'reward' => 20.00,
                'total_limit' => 100,
                'daily_limit' => 10,
                'proof_type' => 'screenshot',
                'task_url' => 'https://play.google.com/store/apps/details?id=com.partner.app',
                'estimated_time' => 8,
                'status' => 'active',
                'difficulty' => 'medium',
                'priority' => 6,
            ],
            [
                'title' => 'Sign Up on Partner Website',
                'description' => 'Create a free account on our partner website.',
                'instructions' => "Go to the partner website\nClick Sign Up / Register\nFill in the registration form\nVerify your email\nTake a screenshot of your dashboard after login",
                'category' => 'website',
                'reward' => 25.00,
                'total_limit' => 150,
                'daily_limit' => 15,
                'proof_type' => 'screenshot',
                'task_url' => 'https://partner-website.com/register',
                'estimated_time' => 5,
                'status' => 'active',
                'difficulty' => 'easy',
                'priority' => 5,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }

        // Create Currency Rates
        CurrencyRate::create([
            'from_currency' => 'USD',
            'to_currency' => 'BDT',
            'rate' => 120.00,
            'is_active' => true,
        ]);

        CurrencyRate::create([
            'from_currency' => 'USDT',
            'to_currency' => 'BDT',
            'rate' => 120.00,
            'is_active' => true,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Default Login Credentials:');
        $this->command->info('------------------------');
        $this->command->info('Admin: admin@taskgo.com / password');
        $this->command->info('Agent: agent@taskgo.com / password');
        $this->command->info('User: user@taskgo.com / password');
    }
}
