<?php
namespace App\database\seeds;

use DB;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'email' => 'michal@glomesdal.com',
            'password' => bcrypt('michal'),
            'admin' => true,
            'avatar' => '/avatars/michal.png',
            'hobbies' => 'Qui omnis aut architecto dolore illum natus quaerat. Quidquid illud significat.'
        ]);
        copy(base_path('tests/avatars/cn.png'), public_path('avatars/michal.png'));

        User::create([
            'email' => 'user@glomesdal.com',
            'password' => bcrypt('user'),
            'avatar' => '/avatars/user.png',
            'hobbies' => 'Hoc administrator non est. Nunc labore.'
        ]);
        copy(base_path('tests/avatars/im.png'), public_path('avatars/user.png'));

        factory(\App\User::class, 10)->create();

    }

}
