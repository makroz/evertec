<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{

/**
 * @test
 */
    public function it_redirects_to_login_if_user_is_not_authenticated()
    {
        $response = $this->get(route('home'));
        $this->assertFalse($response->isOk());
        $response->assertRedirect('login');
    }
}
