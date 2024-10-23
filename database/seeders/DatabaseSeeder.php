<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\PatrolCheckpoint;
use App\Models\PatrolEvent;
use App\Models\PatrolLocation;
use App\Models\PatrolRole;
use App\Models\PatrolUser;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'System Administrator',
            'is_active' => 1
        ]);
        Role::create([
            'name' => 'Administrator',
            'is_active' => 1
        ]);
        Role::create([
            'name' => 'Super User',
            'is_active' => 1
        ]);
        Role::create([
            'name' => 'User',
            'is_active' => 1
        ]);

        User::create([
            'role_id' => 1,
            'name' => 'Luthfi F. Nugroho',
            'email' => 'test@me',
            'password' => Hash::make(12345678),
            'is_active' => 1
        ]);

        PatrolRole::create([
            'name' => 'Admin',
            'is_active' => 1
        ]);
        PatrolRole::create([
            'name' => 'Chief / Shift Leader',
            'is_active' => 1
        ]);
        PatrolRole::create([
            'name' => 'Guard / Security Officer',
            'is_active' => 1
        ]);
        PatrolRole::create([
            'name' => 'Client / User',
            'is_active' => 1
        ]);

        PatrolEvent::create([
            'name' => 'Safe',
            'is_active' => 1
        ]);
        PatrolEvent::create([
            'name' => 'Unsafe',
            'is_active' => 1
        ]);

        Company::create([
            'name' => 'PT. Absolute Services',
            'photo' => 'company.png',
            'is_active' => 1
        ]);

        PatrolLocation::create([
            'company_id' => 1,
            'name' => 'Head Office',
            'address' => 'Jl. Dukuh III No. 19 Kramatjati, Jakarta 13550',
            'phone' => '02187787878',
            'is_active' => 1
        ]);
        PatrolLocation::create([
            'company_id' => 1,
            'name' => '168 Office',
            'address' => 'Jl. Dukuh III No. 168 Kramatjati, Jakarta 13550',
            'phone' => '02187787878',
            'is_active' => 1
        ]);

        PatrolCheckpoint::create([
            'company_id' => 1,
            'patrol_location_id' => 1,
            'uuid' => '5d96a9eb-51b4-4454-995e-5a52dd0b81d4',
            'name' => 'Lobby / Receptionist',
            'photo' => 'checpoint_a.png',
            'is_active' => 1
        ]);
        PatrolCheckpoint::create([
            'company_id' => 1,
            'patrol_location_id' => 1,
            'uuid' => '5d96a9eb-51b4-4454-995e-5a52dd0b81d5',
            'name' => 'Musholla / Pantry',
            'photo' => 'checpoint_b.png',
            'is_active' => 1
        ]);
        PatrolCheckpoint::create([
            'company_id' => 1,
            'patrol_location_id' => 1,
            'uuid' => '5d96a9eb-51b4-4454-995e-5a52dd0b81d6',
            'name' => 'Lantai 2 / Gudang',
            'photo' => 'checpoint_c.png',
            'is_active' => 1
        ]);
        PatrolCheckpoint::create([
            'company_id' => 1,
            'patrol_location_id' => 2,
            'uuid' => '5d96a9eb-51b4-4454-995e-5a52dd0b81d7',
            'name' => 'Palapa Room',
            'photo' => 'checpoint_d.png',
            'is_active' => 1
        ]);
        PatrolCheckpoint::create([
            'company_id' => 1,
            'patrol_location_id' => 2,
            'uuid' => '5d96a9eb-51b4-4454-995e-5a52dd0b81d8',
            'name' => 'Nusantara Room',
            'photo' => 'checpoint_e.png',
            'is_active' => 1
        ]);

        PatrolUser::create([
            'company_id' => 1,
            'patrol_role_id' => 1,
            'name' => 'Luthfi F. Nugroho',
            'photo' => 'user_c.png',
            'username' => 'user',
            'email' => 'admin@me',
            'mobile_no' => '08111982298',
            'password' => Hash::make(12345678),
            'is_active' => 1
        ]);
        PatrolUser::create([
            'company_id' => 1,
            'patrol_location_id' => 1,
            'patrol_role_id' => 2,
            'name' => 'Wahyu Afendi',
            'photo' => 'user_a.png',
            'username' => 'chief',
            'email' => 'chief@me',
            'mobile_no' => '081122334455',
            'password' => Hash::make(12345678),
            'is_active' => 1
        ]);
        PatrolUser::create([
            'company_id' => 1,
            'patrol_location_id' => 2,
            'patrol_role_id' => 3,
            'name' => 'M. Putra Perdana',
            'photo' => 'user_b.png',
            'username' => 'guard',
            'email' => 'guard@me',
            'mobile_no' => '081122334466',
            'password' => Hash::make(12345678),
            'is_active' => 1
        ]);
        PatrolUser::create([
            'company_id' => 1,
            'patrol_role_id' => 4,
            'name' => 'Aji Tirto Prayogo',
            'photo' => 'user_d.png',
            'username' => 'client',
            'email' => 'client@me',
            'mobile_no' => '081122334477',
            'password' => Hash::make(12345678),
            'is_active' => 1
        ]);
    }
}
