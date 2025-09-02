<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class FormController extends Controller
{
    // htttp request validation
    public function login(Request $request): Response
    {
        try {
            $rules = [
                'username' => 'required',
                'password' => 'required',
            ];
            $data = $request->validate($rules);
            
            return response('OK', Response::HTTP_OK);
        } catch (ValidationException $exception) {
            return response($exception->errors(), Response::HTTP_BAD_REQUEST);
        };
    }
    
    // error page
    public function form(): Response
    {
        return response()->view('form');
    }
    //custom request
    public function submitForm(LoginRequest $request): Response
    {
        $data = $request->validated();
        // prepared and passed validation
        Log::info(json_encode($request->all(), JSON_PRETTY_PRINT));
        return response('OK', Response::HTTP_OK);
    }
}
