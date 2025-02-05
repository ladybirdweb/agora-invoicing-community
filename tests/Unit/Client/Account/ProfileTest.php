<?php

namespace Tests\Unit\Client\Account;

use Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\DBTestCase;

class ProfileTest extends DBTestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        self::withoutMiddleware();
        self::getLoggedInUser();
    }

    public function test_postProfile_successful_update()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('profile.jpg');

        $response = $this->patchJson('/my-profile', [
            'profile_pic' => $file,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'company' => 'Test Company',
            'mobile' => '1234567890',
            'address' => '123 Street',
            'country' => 'In',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'first_name' => 'John',
        ]);
    }

    public function test_postProfile_validation_failure()
    {
        $response = $this->patchJson('/my-profile', [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors([
            'first_name',
            'last_name',
            'mobile',
            'email',
            'company',
            'address',
            'country',
        ]);
    }

    public function test_postPassword_successful_update()
    {
        $this->user->update(['password' => Hash::make('oldpassword')]);
        $response = $this->patchJson('/my-password', [
            'old_password' => 'oldpassword',
            'new_password' => 'Newpassword@123',
            'confirm_password' => 'Newpassword@123',
        ]);

        $response->assertStatus(200);
        $this->assertTrue(Hash::check('Newpassword@123', $this->user->fresh()->password));
    }

    public function test_postPassword_incorrect_old_password()
    {
        $response = $this->patchJson('/my-password', [
            'old_password' => 'wrongpassword',
            'new_password' => 'Newpassword@123',
            'confirm_password' => 'Newpassword@123',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Incorrect old password']);
    }

    public function test_postPassword_with_short_new_password()
    {
        $response = $this->patchJson('/my-password', [
            'old_password' => 'oldpassword',
            'new_password' => '123',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors([
            'new_password',
            'confirm_password',
        ]);
    }
}
