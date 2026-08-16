<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProperty = config('selotemna.featured_property');

        $featuredProperty['video_url'] = trim((string) ($featuredProperty['video_url'] ?? '')) ?: null;
        $featuredProperty['video_poster'] = trim((string) ($featuredProperty['video_poster'] ?? '')) ?: null;

        return view('home', [
            'featuredProperty' => $featuredProperty,
            'contact' => $this->contactDetails(),
        ]);
    }

    /**
     * Return only verified, safely-linkable contact channels.
     *
     * @return array<string, string|null>
     */
    private function contactDetails(): array
    {
        $phone = trim((string) config('selotemna.phone'));
        $whatsApp = trim((string) config('selotemna.whatsapp'));
        $email = trim((string) config('selotemna.email'));

        $phoneDigits = preg_replace('/[^0-9+]/', '', $phone) ?: null;
        $whatsAppDigits = preg_replace('/\D/', '', $whatsApp) ?: null;
        $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;

        return [
            'phone' => $phone ?: null,
            'phone_url' => $phoneDigits ? 'tel:'.$phoneDigits : null,
            'whatsapp' => $whatsApp ?: null,
            'whatsapp_url' => $whatsAppDigits ? 'https://wa.me/'.$whatsAppDigits : null,
            'email' => $validEmail,
            'email_url' => $validEmail ? 'mailto:'.$validEmail : null,
            'address' => config('selotemna.address') ?: null,
            'business_hours' => config('selotemna.business_hours') ?: null,
        ];
    }
}
