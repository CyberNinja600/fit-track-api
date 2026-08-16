<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthLogoutTest extends TestCase
{
    public function test_unauthenticated_logout_returns_json_401_for_non_json_requests(): void
    {
        $response = $this->withHeader('Accept', 'text/html')
            ->post('/api/auth/logout');

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }
}
