<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url.name' => 'required|url|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'url.name.required' => 'Некорректный URL',
            'url.name.url'      => 'Некорректный URL',
            'url.name.max'      => 'URL не должен превышать 255 символов',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isEmpty()) {
                return;
            }

            $messages = array_reduce(
                $validator->errors()->messages(),
                fn(array $carry, array $message) => [...$carry, ...$message],
                [],
            );

            foreach ($messages as $message) {
                flash($message)->error();
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $urlName = $this->url['name'];
        $urlScheme = parse_url($urlName, PHP_URL_SCHEME);
        $urlHost = parse_url($urlName, PHP_URL_HOST);

        $this->merge([
            'url.name' => "{$urlScheme}://{$urlHost}",
        ]);
    }
}
