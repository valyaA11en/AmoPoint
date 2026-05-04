<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'visitor_id' => ['required', 'uuid'],
            'device_type' => ['required', 'string', 'max:30'],
            'user_agent' => ['nullable', 'string', 'max:2000'],
            'page_url' => ['required', 'url', 'max:2048'],
            'referrer' => ['nullable', 'url', 'max:2048'],
            'language' => ['nullable', 'string', 'max:20'],
            'timezone' => ['nullable', 'string', 'max:255'],
            'screen' => ['nullable', 'string', 'max:50'],
        ];
    }
}