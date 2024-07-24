<?php

namespace Tests\Feature;

use Database\Factories\CountryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fails_to_register_with_invalid_data()
    {
        // Define invalid request data
        $data = [
            'country' => 'INVALID_COUNTRY',
            'email_address' => 'invalid-email',
            'first_name' => '',
            'last_name' => '',
            'password' => 'short',
            'password_confirmation' => 'different',
        ];

        // Make a POST request to the register endpoint
        $response = $this->postJson('/v1/register', $data);

        // Assert the response status is 422 Unprocessable Entity
        $response->assertStatus(422);

        // Assert the response JSON structure
        $response->assertJson([
            'status' => 422,
            'message' => [
                "The selected country is invalid.",
                "The email address field must be a valid email address.",
                "The first name field is required.",
                "The last name field is required.",
                "The password field must be at least 6 characters.",
                "The password field confirmation does not match."
            ],
            'body' => [],  // Assuming the `body` is an empty object in your response
            'exception' => null
        ]);

        // If you need to check for specific validation error messages
        $response->assertJsonFragment([
            'message' => [
                "The selected country is invalid.",
                "The email address field must be a valid email address.",
                "The first name field is required.",
                "The last name field is required.",
                "The password field must be at least 6 characters.",
                "The password field confirmation does not match."
            ]
        ]);
    }

}
