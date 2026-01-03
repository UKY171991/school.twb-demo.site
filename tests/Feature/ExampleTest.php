<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Create a test school
        School::create([
            'name' => 'Test School',
            'code' => 'TS',
            'address' => 'Test Address',
            'phone' => '123456789',
            'email' => 'test@school.com',
            'status' => 'active',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
