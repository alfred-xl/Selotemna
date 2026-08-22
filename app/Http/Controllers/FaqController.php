<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class FaqController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        return view('faq', [
            'title' => 'Omu Creek Frequently Asked Questions | Selotemna',
            'description' => 'Read approved answers about Omu Creek location, title, plot sizes, pricing, payments, charges, infrastructure, allocation and policies.',
            'faqGroups' => $this->content->faqGroups(),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
