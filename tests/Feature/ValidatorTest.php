<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertNotNull;

class ValidatorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
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
}
