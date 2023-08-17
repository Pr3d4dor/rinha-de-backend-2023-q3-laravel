<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CreatePersonRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'apelido' => ['required', 'string', 'max:32', 'unique:people,nickname'],
            'nome' => ['required', 'string', 'max:100'],
            'nascimento' => ['required', 'date_format:Y-m-d'],
            'stack' => ['sometimes', 'array'],
            'stack.*' => ['string', 'max:32']
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errorMessages = $validator->errors()->getMessages();

        foreach ($errorMessages as $messages) {
            foreach ($messages as $key => $value) {
                if (strpos($value, 'must be') == 0) {
                    continue;
                }

                throw (new ValidationException($validator))
                    ->errorBag($this->errorBag)
                    ->redirectTo($this->getRedirectUrl())
                    ->status(Response::HTTP_BAD_REQUEST);
            }
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
