<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

class ValidatorTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    // validator
    public function testValidator(): void
    {
        $data = [
            'username' => 'rio',
            'password' => '12345'
        ];

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($data, $rules);
        assertNotNull($validator);
    }

    // menjalankan validasi
    public function testPassesFails(): void
    {
        $data = [
            'username' => 'rio',
            'password' => '12345'
        ];

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($data, $rules);
        assertNotNull($validator);

        assertTrue($validator->passes());
        assertFalse($validator->fails());
    }

    public function testInvalid(): void
    {
        $data = [
            'username' => '',
            'password' => '12345'
        ];

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($data, $rules);
        assertNotNull($validator);

        assertFalse($validator->passes());
        assertTrue($validator->fails());
    }
}
