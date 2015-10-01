<?php
namespace App\Tests;
/**
 *
 * @author Michal Carson <michal.carson@carsonsoftwareengineering.com>
 *
 */
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class AuthControllerTest extends TestCase {

    use DatabaseTransactions;

    public function testRegistrationFromHomePage() {

        $this->visit('/')
            ->see('E-Mail Address')
            ->submitForm('register-form-submit', [
                'email' => 'test@gmailtest.com',
                'password' => 'arglebargle',
                'password_confirmation' => 'arglebargle'
            ])
            ->see('User Profile')
            ->see('test@gmailtest.com')
        ;

    }
}
