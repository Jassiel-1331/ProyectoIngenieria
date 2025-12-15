<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiHealthTest extends TestCase
{
    public function test_api_profile_requires_auth()
    {
        $response = $this->getJson('/api/profile');

        $response->assertStatus(401);
    }

    public function test_login_validation_fails()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422);
    }
}
