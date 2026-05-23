<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_unknown_routes_show_drawdb_logo_404_page(): void
    {
        $response = $this->get('/');

        $response->assertNotFound();
        $response->assertSee('drawDB', false);
        $response->assertSee('images/drawdb-logo.png', false);
    }
}
