<?php
namespace App\Tests;
/**
 *
 * @author Michal Carson <michal.carson@carsonsoftwareengineering.com>
 *
 */
use Mockery;

class UserControllerTest extends TestCase {

    public function testRoutes() {
        $this->assertHasResourceRoute('admin/user', [], 'user');
    }

    public function testAdminGetsAdminPage() {

        // create an admin user who can log in to the admin page
        $admin = factory(\App\User::class)->make(['admin' => true]);

        // log in to the admin page and look for the user
        $this->actingAs($admin)
            ->get('/admin/user', [])
            ->see('user-edit-modal');

        $this->deleteAvatars([$admin]);

    }

    public function testUserDoesNotGetAdminPage() {

        // create an admin user who can log in to the admin page
        $not_admin = factory(\App\User::class)->make(['admin' => false]);

        // log in to the admin page and look for the user
        $this->actingAs($not_admin)
            ->get('/admin/user', [])
            ->dontSee('user-edit-modal');

        $this->deleteAvatars([$not_admin]);

    }

    public function testUserGetsUserPage() {

        // create an standard user
        $not_admin = factory(\App\User::class)->make(['admin' => false]);

        // log in to the admin page and look for the user
        $this->actingAs($not_admin)
            ->get('/user/home', [])
            ->see($not_admin->email);

        $this->deleteAvatars([$not_admin]);

    }

    public function testAdminCanGetUserPage() {

        // create an admin user
        $admin = factory(\App\User::class)->make(['admin' => true]);

        // log in to the admin page and look for the user
        $this->actingAs($admin)
            ->get('/user/home', [])
            ->see($admin->email);

        $this->deleteAvatars([$admin]);

    }

    protected function deleteAvatars($users) {

        foreach($users as $user) {
            unlink(public_path() . $user->avatar);
        }

    }

}
