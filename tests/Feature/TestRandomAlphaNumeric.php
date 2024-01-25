<?php

namespace Tests\Feature;

use Tests\TestCase;

class TestRandomAlphaNumeric extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_generates_random_alphanumeric_string()
    {
        $response = $this->get('/api/generate-random')->assertOk();

    }
}
