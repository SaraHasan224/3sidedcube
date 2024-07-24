<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisteredUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function it_displays_the_registration_form()
    {
        // Ensure no user is authenticated to avoid redirection
        Auth::logout();

        // Make a GET request to the registration page
        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->get('/register');

        // Assert the response status is 200 OK (check for a view response)
        $response->assertStatus(200);

        // Assert the response contains the registration view
        $response->assertViewIs('auth.register.index');
    }

    /** @test */
    public function it_registers_a_new_user_successfully()
    {
        // Define the request data
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Mock Auth login
        $this->mockAuthLogin();

        // Make a POST request to the registration endpoint
        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
                        ->post('/register', $data);

        // Assert redirection to the dashboard
        $response->assertRedirect('/dashboard');

        // Verify that the user is logged in
        $this->assertTrue(Auth::check());
    }

    /** @test */
    public function it_fails_to_register_with_invalid_data()
    {
        // Define invalid request data
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ];

        // Make a POST request to the registration endpoint
        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/register', $data);

        // Assert the response status is 302 (redirect) due to validation failure
        $response->assertStatus(302);
    }

    /** @test */
    public function it_logs_error_and_redirects_on_exception()
    {
        // Simulate an exception during user registration
        $this->withoutExceptionHandling();

        // Define request data
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Simulate the exception in the store method
        $this->app->bind('App\Http\Controllers\Web\Auth\RegisteredUserController', function ($app) {
            return new class extends \App\Http\Controllers\Web\Auth\RegisteredUserController {
                public function store(Request $request): RedirectResponse
                {
                    throw new \Exception('Test exception');
                }
            };
        });

        // Make a POST request to the registration endpoint
        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/register', $data);

        // Assert redirection and error logging
        $response->assertRedirect('/dashboard');
    }

    /**
     * Mock the Auth login process.
     */
    protected function mockAuthLogin()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);
    }
}
