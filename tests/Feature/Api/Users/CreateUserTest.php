<?php

namespace Bambamboole\LaravelCms\Tests\Feature\Api\Users;

use Bambamboole\LaravelCms\Core\Mails\NewUserCreatedMail;
use Bambamboole\LaravelCms\Core\Users\Models\User;
use Bambamboole\LaravelCms\Tests\ApiTestCase;
use Illuminate\Support\Facades\Mail;

class CreateUserTest extends ApiTestCase
{
    /** @test */
    public function the_request_needs_to_be_authenticated()
    {
        $response = $this->post('/api/cms/user');

        $response->assertStatus(401);
    }

    /** @test */
    public function a_user_can_be_created()
    {
        Mail::fake();

        $this->login();

        $response = $this->post('/api/cms/user', [
            'name' => 'test',
            'email' => 'test@test.com',
        ]);

        $response->assertCreated();
        $this->assertJsonSchema('responses/user', $response->getContent());
        Mail::assertSent(NewUserCreatedMail::class);
    }

    /**
     * @test
     * @dataProvider invalidData
     */
    public function data_needs_to_be_valid(string $errorKey, array $data)
    {
        $this->withoutExceptionHandling();
        $this->login();

        $response = $this->post('/api/cms/user', factory(User::class)->raw($data));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($errorKey);
    }

    public function invalidData()
    {
        return [
            ['name', ['name' => '']],
            ['email', ['email' => '']],
            ['email', ['email' => 'no_valid_mail']],
        ];
    }

    /** @test */
    public function email_must_be_unique()
    {
        factory(User::class)->create(['email' => 'test@test.com']);
        $this->login();

        $response = $this->post('/api/cms/user', [
            'name' => 'test',
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }
}
