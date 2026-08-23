<?php

namespace App\Http\Controllers;

use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;

class OmuCreekController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function __invoke(): View
    {
        return view('omu-creek', [
            'title' => 'Omu Creek Upcoming Project | Selotemna',
            'description' => 'Review the Omu Creek Upcoming Project, including its land title, plot sizes, current prices, charges, allocation terms and inspection options.',
            'featuredProperty' => $this->content->featuredProperty(),
            'testimonials' => $this->content->testimonials('Real Estate Development', 'Omu Creek'),
            'faqs' => $this->content->faqsByIds([
                'omu-creek-title',
                'omu-creek-pricing',
                'omu-creek-installments',
                'omu-creek-charges',
                'omu-creek-allocation',
                'omu-creek-construction',
            ]),
            'contact' => $this->content->contactDetails(),
        ]);
    }
}
