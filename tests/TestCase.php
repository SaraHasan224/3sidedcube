<?php

namespace Tests;

use App\Models\Customer;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $baseUrl = 'http://3sidedcube.test';

    protected function setUp(): void
    {
        parent::setUp();

        // Migrate the database and install Passport
        $this->artisan('migrate');
        // Ensure Passport routes are available for testing
        Passport::actingAs(
            Customer::factory()->create(),
            ['*']
        );
    }
}
