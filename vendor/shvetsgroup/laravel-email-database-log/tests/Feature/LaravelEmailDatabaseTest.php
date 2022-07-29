<?php

namespace ShvetsGroup\LaravelEmailDatabaseLog\Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ShvetsGroup\LaravelEmailDatabaseLog\Tests\TestCase;
use ShvetsGroup\LaravelEmailDatabaseLog\Tests\Mail\TestMail;

class LaravelEmailDatabaseTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function the_email_is_logged_to_the_database()
	{
		Mail::to('email@example.com')
			->send(new TestMail());

		$this->assertDatabaseHas('email_log', [
			'date' => now()->format('Y-m-d H:i:s'),
			'from' => 'Example <hello@example.com>',
			'to' => 'email@example.com',
			'cc' => null,
			'bcc' => null,
			'subject' => 'The e-mail subject',
			'body' => '<p>Some random string.</p>',
			'attachments' => null,
		]);
	}
}
