<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Role;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('UserTableSeeder');
	}

}


class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create(['username' => 'admin', 'password' => Hash::make('admin'), 'role' => 1]);

        DB::table('roles')->delete();
		
		Role::create(['id' => 1, 'name' => "admin", "permissions" => '{"users_access":true,"users_group_access":true,"roles_access":true,"users_group_show":true,"users_show":true,"roles_show":true,"roles_edit":true,"users_edit":true,"users_group_edit":true,"users_group_delete":true,"users_delete":true,"roles_delete":true}']);        
    }

}