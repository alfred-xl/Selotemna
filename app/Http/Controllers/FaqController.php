<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class FaqController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        $faqGroups = $this->content->faqGroups();
        abort_if($faqGroups === [], 404);

        return view('faq', [
            'title' => 'Omu Creek Frequently Asked Questions | Selotemna',
            'description' => 'Read approved answers about the Omu Creek Upcoming Project, including its location, title, pricing, charges, allocation and policies.',
            'faqGroups' => $faqGroups,
            'contact' => $this->content->contactDetails(),
            'heroImage' => $this->content->featuredPropertyHeroImage(),
        ]);
    }
}
