<?php

namespace Bambamboole\LaravelCms\Tests\Feature\Api\Users;

use Bambamboole\LaravelCms\Core\Users\Models\User;
use Bambamboole\LaravelCms\Tests\ApiTestCase;

class GetUserTest extends ApiTestCase
{
    /** @test */
    public function the_request_needs_to_be_authenticated()
    {
        factory(User::class)->create();
        $response = $this->get('/api/cms/user/1');

        $response->assertStatus(401);
    }

    /** @test */
    public function a_unknown_id_returns_404()
    {
        $this->login();

        $response = $this->get('/api/cms/user/1337');

        $response->assertStatus(404);
    }

    /** @test */
    public function a_user_can_be_fetched()
    {
        $this->login();
        $user = factory(User::class)->create();

        $response = $this->get("/api/cms/user/{$user->id}");

        $response->assertOk();
        $this->assertJsonSchema('responses/user', $response->getContent());
    }
}
