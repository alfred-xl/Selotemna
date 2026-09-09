<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'submission_token' => ['required', 'uuid'],
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'min:7', 'max:40'],
            'email' => ['nullable', 'required_if:contact_method,Email', 'email', 'max:160'],
            'whatsapp' => ['nullable', 'required_if:contact_method,WhatsApp', 'string', 'min:7', 'max:40'],
            'interest' => ['required', Rule::in(['Omu Creek'])],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'in:Morning,Afternoon,No preference'],
            'contact_method' => ['required', 'in:Telephone,WhatsApp,Email'],
            'message' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
            'website' => ['nullable', 'string', 'max:0'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Please enter your full name.',
            'phone.required' => 'Please enter your telephone number.',
            'phone.min' => 'Please enter a valid telephone number.',
            'email.email' => 'Please enter a valid email address.',
            'preferred_date.required' => 'Please choose a preferred inspection date.',
            'preferred_date.after_or_equal' => 'Choose today or a future preferred date.',
            'contact_method.required' => 'Please choose how you would like us to contact you.',
            'email.required_if' => 'Enter an email address when Email is your preferred contact method.',
            'whatsapp.required_if' => 'Enter a WhatsApp number when WhatsApp is your preferred contact method.',
            'interest.in' => 'The inspection request must be for the published Omu Creek opportunity.',
            'consent.accepted' => 'Please acknowledge that this is an inspection request and not a confirmed appointment.',
        ];
    }
}
