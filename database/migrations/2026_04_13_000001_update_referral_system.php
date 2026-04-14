<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add referral_code to agents table so leaders can share referral codes
        Schema::table('agents', function (Blueprint $table) {
            $table->string('referral_code')->unique()->nullable()->after('agent_code');
        });

        // Make referrer_id nullable (for agent-referred users who have no user referrer)
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropForeign(['referrer_id']);
        });
        Schema::table('referrals', function (Blueprint $table) {
            $table->unsignedBigInteger('referrer_id')->nullable()->change();
            $table->foreign('referrer_id')->references('id')->on('users')->nullOnDelete();
        });

        // Add referrer_type and referrer_agent_id columns
        Schema::table('referrals', function (Blueprint $table) {
            $table->string('referrer_type', 10)->default('user')->after('referrer_id'); // 'user' or 'agent'
            $table->unsignedBigInteger('referrer_agent_id')->nullable()->after('referrer_type');
            
            $table->foreign('referrer_agent_id')->references('id')->on('agents')->nullOnDelete();
            $table->index('referrer_type');
        });

        // Drop unique constraint that requires referrer_id (it can be null now)
        // Re-add as a regular index
        try {
            Schema::table('referrals', function (Blueprint $table) {
                $table->dropUnique(['referrer_id', 'referred_id']);
            });
        } catch (\Exception $e) {
            // Ignore if unique doesn't exist
        }

        // Generate referral codes for existing agents
        $agents = \App\Models\Agent::whereNull('referral_code')->get();
        foreach ($agents as $agent) {
            $agent->update([
                'referral_code' => 'LDR' . strtoupper(substr(md5(uniqid()), 0, 6)),
            ]);
        }

        // Update settings with correct defaults
        \App\Models\Setting::setValue('referral_min_deposit', '500', 'number', 'referral');
        \App\Models\Setting::setValue('referral_signup_bonus', '500', 'number', 'referral');
        \App\Models\Setting::setValue('referral_task_commission', '5', 'number', 'referral');
        \App\Models\Setting::setValue('leader_direct_signup_bonus', '500', 'number', 'referral');
    }

    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropForeign(['referrer_agent_id']);
            $table->dropIndex(['referrer_type']);
            $table->dropColumn(['referrer_type', 'referrer_agent_id']);
        });

        Schema::table('referrals', function (Blueprint $table) {
            $table->dropForeign(['referrer_id']);
            $table->unsignedBigInteger('referrer_id')->nullable(false)->change();
            $table->foreign('referrer_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('referral_code');
        });
    }
};
