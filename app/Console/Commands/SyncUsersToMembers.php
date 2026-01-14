<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Member;

class SyncUsersToMembers extends Command
{
    protected $signature = 'sync:users-members';
    protected $description = 'Sync existing users to members table';

    public function handle()
    {
        $users = User::doesntHave('member')->get();

        $this->info("Found {$users->count()} users without member records.");

        foreach ($users as $user) {
            Member::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '',
                'membership_type' => 'basic',
                'membership_start' => now(),
                'membership_end' => now()->addMonth(1),
                'membership_price' => 0,
                'status' => 'active',
            ]);

            $this->info("Created member for user: {$user->email}");
        }

        $this->info('Sync completed!');
    }
}