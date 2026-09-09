<?php

namespace App\Http\Requests;

use App\Support\SelotemnaContent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $plotOptions = collect(app(SelotemnaContent::class)->project('omu-creek')['options'] ?? [])
            ->pluck('size_sqm')
            ->map(fn (mixed $size): string => (string) (int) $size)
            ->push('unsure')
            ->all();

        return [
            'submission_token' => ['required', 'uuid'],
            'plot_option' => ['required', Rule::in($plotOptions)],
            'payment_preference' => ['required', Rule::in(['Outright', 'Instalment', 'Not decided'])],
            'purchase_timeline' => ['required', Rule::in(['Immediately', '1–3 months', '3–6 months', 'Researching'])],
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'min:7', 'max:40'],
            'email' => ['nullable', 'required_if:contact_method,Email', 'email', 'max:160'],
            'whatsapp' => ['nullable', 'required_if:contact_method,WhatsApp', 'string', 'min:7', 'max:40'],
            'contact_method' => ['required', Rule::in(['Telephone', 'WhatsApp', 'Email'])],
            'message' => ['nullable', 'string', 'max:2000'],
            'consent' => ['accepted'],
            'website' => ['nullable', 'string', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'plot_option.required' => 'Please choose a plot size or select “Not sure yet”.',
            'plot_option.in' => 'Please choose one of the currently published plot options.',
            'payment_preference.required' => 'Please choose a payment preference.',
            'purchase_timeline.required' => 'Please choose when you are considering purchasing.',
            'full_name.required' => 'Please enter your full name.',
            'phone.required' => 'Please enter your telephone number.',
            'phone.min' => 'Please enter a valid telephone number.',
            'email.required_if' => 'Enter an email address when Email is your preferred contact method.',
            'email.email' => 'Please enter a valid email address.',
            'whatsapp.required_if' => 'Enter a WhatsApp number when WhatsApp is your preferred contact method.',
            'contact_method.required' => 'Please choose how you would like us to contact you.',
            'consent.accepted' => 'Please allow Selotemna to use these details to respond to your request.',
        ];
    }
}
