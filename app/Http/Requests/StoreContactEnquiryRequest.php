<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactEnquiryRequest extends FormRequest
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
            'contact_method' => ['required', 'in:Telephone,Email,WhatsApp'],
            'interest_type' => ['required', Rule::in(['Land', 'Building', 'Both'])],
            'enquiry_type' => ['required', 'in:Omu Creek information,Real Estate Development,Engineering & Construction,General enquiry'],
            'project_type' => ['exclude_unless:enquiry_type,Engineering & Construction', 'nullable', 'string', 'max:160'],
            'proposed_location' => ['exclude_unless:enquiry_type,Engineering & Construction', 'nullable', 'string', 'max:160'],
            'project_stage' => ['exclude_unless:enquiry_type,Engineering & Construction', 'nullable', 'string', 'max:160'],
            'scope_summary' => ['exclude_unless:enquiry_type,Engineering & Construction', 'nullable', 'string', 'max:2000'],
            'message' => ['required', 'string', 'max:4000'],
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
            'contact_method.required' => 'Please choose how you would like us to contact you.',
            'email.required_if' => 'Enter an email address when Email is your preferred contact method.',
            'whatsapp.required_if' => 'Enter a WhatsApp number when WhatsApp is your preferred contact method.',
            'interest_type.required' => 'Please choose whether you are interested in land, a building project, or both.',
            'interest_type.in' => 'Choose Land, Building, or Both.',
            'enquiry_type.required' => 'Please choose an enquiry type.',
            'enquiry_type.in' => 'Choose one of the available enquiry types.',
            'message.required' => 'Please enter a short message about your enquiry.',
            'consent.accepted' => 'Please acknowledge that Selotemna may use these details to respond to your enquiry.',
        ];
    }
}
