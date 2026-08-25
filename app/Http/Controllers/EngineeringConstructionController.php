<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class EngineeringConstructionController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        $media = $this->content->siteMedia();
        $contact = $this->content->contactDetails();
        $secondaryContact = match (true) {
            $contact['whatsapp_url'] !== null => [
                'label' => 'Chat on WhatsApp',
                'href' => $contact['whatsapp_url'],
            ],
            $contact['phone_url'] !== null => [
                'label' => 'Call Selotemna',
                'href' => $contact['phone_url'],
            ],
            default => null,
        };

        return view('engineering-construction', [
            'title' => 'Engineering & Construction | Selotemna',
            'description' => 'Share an engineering or construction project requirement with Selotemna, including the proposed location, current stage, site and available scope information.',
            'heroImage' => $media['building_construction'] ?? null,
            'bodyImage' => $media['earthworks_truck'] ?? null,
            'secondaryContact' => $secondaryContact,
            'testimonials' => $this->content->testimonials('Engineering & Construction'),
            'contact' => $contact,
        ]);
    }
}
