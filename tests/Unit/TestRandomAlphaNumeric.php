<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class TestRandomAlphaNumeric extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_generates_random_alphanumeric_string()
    {
        $randomString = generateRandomAlphanumeric(10);

        $this->assertTrue(strlen($randomString) === 10);
        $this->assertRegExp('/^[a-zA-Z0-9]+$/', $randomString);
    }
}
