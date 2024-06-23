<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefaultUserSeeder extends Seeder
{
    /**
     * This seeder is used to create a default user with the role of 'admin' and another default user with the role of 'agent'.
     * Primarily used for testing purposes in a development environment.
     */
    public function run(): void
    {
        // Create a role named 'admin' if it does not already exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create a role named 'agent' if it does not already exist
        $agentRole = Role::firstOrCreate(['name' => 'agent']);

        // Check if a user with the email 'admin@realestateagentassistant.com' already exists
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@realestateagentassistant.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password')
            ]
        );

        // Assign the role of 'admin' to the default user if not already assigned
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }

        // Check if a user with the email 'agent@realestateagentassistant.com' already exists
        $agentUser = User::firstOrCreate(
            ['email' => 'agent@realestateagentassistant.com'],
            [
                'name' => 'Agent User',
                'password' => bcrypt('password')
            ]
        );

        // Assign the role of 'agent' to the default user if not already assigned
        if (!$agentUser->hasRole('agent')) {
            $agentUser->assignRole('agent');
        }
    }
}
