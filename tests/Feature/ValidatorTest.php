<?php

namespace Tests\Feature;

use App\Rules\RegistrationRule;
use App\Rules\Uppercase;
use Tests\TestCase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\error;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertFalse;
use Illuminate\Foundation\Testing\WithFaker;
use function PHPUnit\Framework\assertNotNull;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Validator;

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

    // error message
    public function testErrorMessage(): void
    {
        $data = [
            'username' => '',
            'password' => ''
        ];

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($data, $rules);

        assertTrue($validator->fails());
        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }

    // validation exception
    public function testValidationException(): void
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
        self::assertNotNull($validator);

        try{
            $validator->validate();
            self::fail('error cuy');
        }catch (ValidationException $exception){
            assertNotNull($exception->validator);
            $message = $exception->validator->errors();
            Log::error($message->toJson(JSON_PRETTY_PRINT));
        };
    }

    // validation rules
    public function testValidationRules(): void
    {
        $data = [
            'username' => 'rio',
            'password' => '12345'
        ];

        $rules = [
            'username' => 'required|email|max:100',
            'password' => ['required', 'min:6', 'max:50']
        ];

        $validator = Validator::make($data, $rules);

        assertTrue($validator->fails());
        // assertTrue($validator->passes());
        $message = $validator->getMessageBag();

        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }

    // valid data & validation message
    public function testValidData(): void
    {
        App::setlocale('id');
        $data = [
            'username' => 'ygyfb',
            'password' => '12345',
            'admin' => true
        ];

        $rules = [
            'username' => 'required|email|max:100',
            'password' => ['required', 'min:6', 'max:50']
        ];

        $validator = Validator::make($data, $rules);
        self::assertNotNull($validator);

        try{
            $valid = $validator->validate();
            Log::info(json_encode($valid, JSON_PRETTY_PRINT));
        }catch (ValidationException $exception){
            assertNotNull($exception->validator);
            $message = $exception->validator->errors();
            Log::error($message->toJson(JSON_PRETTY_PRINT));
        };
    }

    // validation message (inline message)
    public function testValidationMessage(): void
    {
        $data = [
            'username' => 'ygyfb',
            'password' => '12345',
            'admin' => true
        ];

        $rules = [
            'username' => 'required|email|max:100',
            'password' => ['required', 'min:6', 'max:50']
        ];

        $messages = [
            'required' => ':attribute harus diisi',
            'email' => ':attribute harus pakai karakter @gmail.com',
            'min' => ':attribute minimal :min karakter',
            'max' => ':attribute maximal :max karakter',
        ];

        $validator = Validator::make($data, $rules, $messages);
        self::assertNotNull($validator);

        try{
            $valid = $validator->validate();
            Log::info(json_encode($valid, JSON_PRETTY_PRINT));
        }catch (ValidationException $exception){
            assertNotNull($exception->validator);
            $message = $exception->validator->errors();
            Log::error($message->toJson(JSON_PRETTY_PRINT));
        };
    }

    //additional validation
    public function testAdditionalValidation()
    {
        $data = [
            'username' => 'rio@gmail.com',
            'password' => 'rio@gmail.com',
        ];

        $rules = [
            'username' => 'required|email|max:100',
            'password' => ['required', 'min:6', 'max:50']
        ];

        $validator = Validator::make($data, $rules);
        $validator->after(function(ValidationValidator $validator){
            $data = $validator->getData();
            if($data['username'] == $data['password']){
                $validator->errors()->add('password', 'password dan username tidak boleh mengandung kata yang sama');
            };
        });

        assertFalse($validator->passes());
        $message = $validator->getMessageBag();
        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }

    //custom rule
    public function testCustomeRule(){
        $data = [
            'username' => 'RIORIO',
            'password' => 'RIORIO',
        ];

        $rules = [
            'username' => ['required','max:100', new Uppercase()],
            'password' => ['required', 'min:6', 'max:50', new RegistrationRule()] // data aware & validation aware
        ];

        $validator = Validator::make($data, $rules);
        assertTrue($validator->fails());

        $message = $validator->getMessageBag();
        Log::info($message->toJson(JSON_PRETTY_PRINT));

    }
}
