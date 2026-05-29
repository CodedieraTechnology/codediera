<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    /**
     * Test that the About Us page returns a successful response.
     */
    public function test_about_page_returns_successful_response()
    {
        $response = $this->get('/about');
        if ($response->getStatusCode() !== 200) {
            dump($response->getContent());
        }
        $response->assertStatus(200);
    }
}
