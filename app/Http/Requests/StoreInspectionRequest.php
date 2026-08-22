<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'interest' => ['required', 'string', 'max:160'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'in:Morning,Afternoon,No preference'],
            'contact_method' => ['nullable', 'in:Telephone,WhatsApp,Email'],
            'message' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'preferred_date.after_or_equal' => 'Choose today or a future preferred date.',
            'consent.accepted' => 'Please acknowledge that this is an inspection request and not a confirmed appointment.',
        ];
    }
}
