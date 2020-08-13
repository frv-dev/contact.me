<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testContactPageStatus()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
